function timeInSeconds(duration){
    let hhmmss = duration.split(':');
    return (parseInt(hhmmss[0])*60*60) + (parseInt(hhmmss[1])*60) + parseInt(hhmmss[2]);
}
//let
schedule = [...document.querySelectorAll('.days .day')].map((day)=>{
    let departments = [...day.querySelectorAll('.department')].map((department)=>{
        let shifts = [...department.querySelectorAll('.shift.row')];
        let shiftDuration = shifts[0].dataset.duration;
        let departmentName = department.querySelector('.title a').innerText;
        return {
            container: department,
            department: departmentName,
            shifts: shifts.reduce((shifts, shift)=>{

                let shiftName = shift.querySelector('.title a').innerText;
                let slots = [...shift.querySelectorAll('.slots .slot-wrap')];
                console.log(shifts, shiftName);
                shifts[shiftName] = shifts[shiftName] || [];

                let shiftOffset = timeInSeconds(slots[0].dataset.start) % timeInSeconds(slots[0].dataset.duration);
                let matchingSlots = shifts[shiftName].find((test)=>{

                    let testOffset = timeInSeconds(test.slots[0].dataset.start) % timeInSeconds(test.slots[0].dataset.duration);
                    return test.slots[0].dataset.duration === slots[0].dataset.duration &&
                        testOffset === shiftOffset;
                });

                if(matchingSlots){
                    let startIndex = parseInt(matchingSlots[matchingSlots.length-1].dataset.row);

                    //console.log(matchingSlots, slots, shift)
                    debugger;
                }
                else{
                    shifts[shiftName] = shifts[shiftName].concat({
                        shift: shiftName,
                        container: shift,
                        slots
                    });
                }
                return shifts;
            },{})
        };
    });
    return departments;
});

console.log(schedule)
