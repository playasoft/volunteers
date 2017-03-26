const _ = require('lodash');
const $ = require('wetfish-basic');
const Highlight = require('./highlight');
const timegrid = require('./timegrid');

//let test = require('./test.html.tpl');
let grid = require('../../templates/grid.html.tpl');
let previewContainer = null;

$(document).ready(function()
{
    let editForm = document.querySelector('form.edit-schedule');
    if(editForm){
        let previewContainer = editForm.querySelector('.preview');
        let formData = parseForm(formValues(editForm));
        previewContainer.innerHTML = makePreview(formData);

        //this creates a preview whenever the schedule edit form changes
        $('form.edit-schedule').on('change', function(e){
            if(e.target.name === "does_slot_repeat"){
                toggleRepeatInput(e.target);
            }

            updateEndTime();

            updatePreview();
        });

        let repeatToggle = editForm.querySelector('[name="does_slot_repeat"]');
        let slotRepeat   = editForm.querySelector('[name="slot_repeat"]');
        let slotDuration = parseTime(formData.duration);
        let totalLength  = parseTime(formData.end_time)-parseTime(formData.start_time);

        document.querySelector('form.edit-schedule .slot-repeat .slot-end').innerText = formData.end_time;
        //console.log(slotRepeat, formData, Math.floor(totalLength/slotDuration));
        if(typeof totalLength === 'number' && typeof slotDuration ==='number'){
            slotRepeat.value = Math.floor(totalLength/slotDuration);
            console.log(`slot repeats ${slotRepeat.value} times`);
            if(slotRepeat.value !== 0){
                repeatToggle.checked = true;
                editForm.querySelector('.slot-repeat').classList.remove('hidden');
            }
            updatePreview();
        }
    }

});

function toggleRepeatInput(e)
{
    let editForm = document.querySelector('form.edit-schedule');
    let slotRepeatContainer = editForm.querySelector('.slot-repeat');
    let slotRepeatField = editForm.querySelector('[name="slot_repeat"]');

    if(e.checked){
        if(typeof slotRepeatField.value !== 'number'){
            slotRepeatField.value = 1;
        }
        slotRepeatContainer.classList.remove('hidden');
    }
    else{
        slotRepeatContainer.classList.add('hidden');
        slotRepeatField.value = 1;
        updateEndTime();
    }
}

function updateEndTime()
{
    let editForm    = document.querySelector('form.edit-schedule');
    let slotRepeats = parseInt(editForm.querySelector('[name="slot_repeat"]').value || 1);
    let formData    = parseForm(formValues(editForm));
    let duration    = parseTime(formData.duration);
    let shiftLength = duration * slotRepeats;
    let endTime     = parseTime(formData.start_time) + shiftLength;

    // console.log('start, duration, end', parseTime(formData.start_time), parseTime(formData.duration), endTime);

    console.log('end time is now: ',formatTime(endTime), endTime);
    document.querySelector('form.edit-schedule [name="end_time"]').value = formatTime(endTime);
    document.querySelector('form.edit-schedule .slot-repeat .slot-end').innerText = decorateTime(endTime);
}

function updatePreview()
{
    let editForm = document.querySelector('form.edit-schedule');
    let formData = parseForm(formValues(editForm));
    let previewContainer = editForm.querySelector('.preview');
    previewContainer.innerHTML = makePreview(formData);
    timegrid.calculateSlotSizes();
    (previewContainer.querySelectorAll('.shift-wrap')||[]).forEach(function(elem)
    {
        new Highlight(elem);
    });
}

function formValues(form)
{
    // let form  = document.querySelector('form.edit-schedule');

    // select input fields and reduce them into a json representation
    let inputTags  = [...form.querySelectorAll('input')].reduce(function getInputValues(field, e){
        let {name, value, checked} = e;
        switch(e.getAttribute('type')){
        case 'checkbox':
            if(!e.name){
                return field;
            }
            field[name] = field[name] || {
                type: 'checkbox',
                values:[]
            };
            // console.log(e.parent);
            let description="";
            if(e.parentElement.tagName.toLowerCase() === 'label'){
                description=e.parentElement.innerText;
            }

            field[name].values.push({
                value,
                checked,
                description
            });
            return field;
        case 'text':
            field[name]={
                type:'text',
                value
            };
            return field;
        case 'time':
        case 'hidden':
            field[name]={
                type:'time',
                value
            };
        default:
            return field;
        }
    }, {});
    let selectTags = [...form.querySelectorAll('select')].reduce(function getSelectValues(field, e){
        if(e.name){
            //const selection = e.querySelector('option[selected]');
            const {name, value} = e;
            let selectedOption = e.querySelector('option[value="'+value+'"]');

            if(selectedOption){
                field[name] = {
                    type:'select',
                    description: selectedOption.innerHTML,
                    value: (selectedOption||{}).value
                };
            }
        }
        return field;
    }, {});

    //combine the fields into a single object
    let fields = Object.assign({}, inputTags, selectTags);

    return fields;
}

function parseForm(formValues)
{
    if(!formValues.department_id ||
       !formValues.shift_id ||
       !formValues.start_time ||
       !formValues.end_time ||
       !formValues.duration ||
       !formValues.volunteers ||
       !formValues['dates[]']
      )
    {
        return null;
    }
    let days = formValues['dates[]'].values.filter(function getChecked(date){
        return date.checked;
    }).map(function formatDay(day){
        const formattedDay =
        {
            date: day.value,
            name: day.description.match(/^[a-zA-z]+/)[0]
        };
        return formattedDay;
    });

    let department =
    {
        id: parseInt(formValues.department_id.value),
        name: formValues.department_id.description
    };

    let shift =
    {
        id: parseInt(formValues.shift_id.value),
        name: formValues.shift_id.description
    };

    let volunteers = formValues.volunteers.value||null;

    // these could be abstracted to be more concise
    // since the logic is duplicated
    let start_time = formValues.start_time.value||null;
    let end_time = formValues.end_time.value||null;
    let duration = formValues.duration.value||null;
    if(start_time === 'custom')
    {
        start_time = formValues.custom_start_time.value || null;
    }

    if(end_time === 'custom')
    {
        end_time = formValues.custom_end_time.value || null;
    }

    if(duration === 'custom')
    {
        duration = formValues.custom_duration.value || null;
    }

    const formData =
    {
        days,
        department,
        shift,
        start_time,
        duration,
        end_time,
        duration,
        volunteers
    };

    return formData;

}

function parseTime(time)
{
    if(!time){return null;}
    // convert hours and minutes into seconds and then add them together
    let secondsByIndex = [60 * 60, 60, 1];
    return time.split(':').reduce(function(acc, val, idx)
    {
        return acc + (parseInt(val) * secondsByIndex[idx]);
    },0);
}

function normalizeTime(time)
{
    if(!time){return null;}
    // splits on the delimiter and pads each segment with zeros
    let segments = time.split(':').map(function formatSegment(segment)
    {
        while(segment.length<2){
            segment= "0"+segment;
        }
        return segment;
    });

    //adds hours/minutes if implicit
    while(segments.length<3)
    {
        segments.push("00");
    }
    return segments.join(':');
}

function formatTime(seconds)
{
    const hours   = Math.floor(seconds / (60 * 60));
    seconds = seconds - (hours * 60 * 60);
    const minutes = Math.floor(seconds / 60);
    seconds = Math.round(seconds - (minutes * 60));
    //const ampm = (hours < 12)?' AM':' PM';
    return `${_.padStart(hours, 2, '0')}:${_.padStart(minutes,2,'0')}:${_.padStart(seconds,2,'0')}`;
}

function decorateTime(time)
{
    let hours   = Math.floor(time / (60 * 60));
    let seconds = time - (hours * 60 * 60);
    const minutes = Math.floor(seconds / 60);
    seconds = Math.round(seconds - (minutes * 60));
    const ampm = (hours % 24 >= 12) ? 'pm':'am';
    hours = hours % 12;
    hours = hours || 12;
    return `${hours.toString()}:${_.padStart(minutes,2,'0')} ${ampm}`;
}

function makePreview(data)
{
    // @todo allow for shifts to wrap into the next day
    if(!data){
        return null;
    }

    const start = parseTime(normalizeTime(data.start_time));
    const slotDuration = parseTime(normalizeTime(data.duration));
    const end = parseTime(normalizeTime(data.end_time));
    const volunteers = parseInt(data.volunteers);

    let slots = [];
    for(let currentTime = start; currentTime<end; currentTime += slotDuration)
    {
        if((currentTime + slotDuration) <= end) //don't create shifts which go beyond the end date
        {
            for(let row = 1; row <= volunteers; row++){
                slots = slots.concat({
                    id: null,
                    row,
                    start_date: formatTime(currentTime),
                    duration: formatTime(slotDuration),
                    title: `${decorateTime(currentTime)} - ${decorateTime(currentTime+slotDuration )}`
                });
            }
        }
    }

    // format the shifts for each day
    let days = data.days.reduce(function createDays(days, day){
        return days.concat({
            name: day.name,
            date: day.date,
            departments: [{
                id: data.department.id,
                name:data.department.name,
                shifts:[{
                    id: data.shift.id,
                    name: data.shift.name,
                    slots: JSON.parse(JSON.stringify(slots))
                }]
            }]
        });
    }, []);

    return grid({
        name: 'Shift Preview',
        days
    });

}
/*
function _mockData(){
    return {
        name:'apo 2017 hurr',
        days:[{
            name:'sunday',
            date: '2017-02-25',
            departments: [{
                id: 1,
                name:"department of butts",
                shifts:[{
                    id:2,
                    name:'butt inspector',
                    slots:[
                        {
                            "id": "65",
                            "row": "1",
                            "start_date": "00:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "66",
                            "row": "1",
                            "start_date": "06:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "67",
                            "row": "1",
                            "start_date": "12:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "68",
                            "row": "1",
                            "start_date": "18:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "97",
                            "row": "2",
                            "start_date": "00:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "98",
                            "row": "2",
                            "start_date": "06:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "99",
                            "row": "2",
                            "start_date": "12:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "100",
                            "row": "2",
                            "start_date": "18:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "129",
                            "row": "3",
                            "start_date": "00:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "130",
                            "row": "3",
                            "start_date": "06:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "131",
                            "row": "3",
                            "start_date": "12:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "132",
                            "row": "3",
                            "start_date": "18:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "161",
                            "row": "4",
                            "start_date": "00:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "162",
                            "row": "4",
                            "start_date": "06:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "163",
                            "row": "4",
                            "start_date": "12:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "164",
                            "row": "4",
                            "start_date": "18:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "193",
                            "row": "5",
                            "start_date": "00:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "194",
                            "row": "5",
                            "start_date": "06:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "195",
                            "row": "5",
                            "start_date": "12:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "196",
                            "row": "5",
                            "start_date": "18:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "225",
                            "row": "6",
                            "start_date": "00:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "226",
                            "row": "6",
                            "start_date": "06:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "227",
                            "row": "6",
                            "start_date": "12:00:00",
                            "duration": "06:00:00"
                        },
                        {
                            "id": "228",
                            "row": "6",
                            "start_date": "18:00:00",
                            "duration": "06:00:00"
                        }

                    ]
                }]
            }]
        }]
    };
}
*/
window.formValues  = formValues;
window.makePreview = makePreview;
