# Basic project setup

## Auth
- [x] Registration
- [x] Login
- [x] Permissions per page

### User groups
- [x] Admin
- [x] Volunteer
- [x] Veteran
- [x] Medical
- [x] Fire


## Models
- Events
 - [x] Event title
 - [x] Description
 - [x] Dates

- Departments
 - [x] Event
 - [x] Department name
 - [x] Description
 - [x] Allowed user groups
 
- Shifts
 - [x] Department
 - [x] Start
 - [x] End
 - [x] Duration
 - [x] Allowed user groups

- Slots
 - [ ] Shift
 - [ ] Start
 - [ ] User


## Pages
- [ ] Viewing your own profile
- [ ] Editing your own profile
- [ ] Viewing list of shifts you've signed up for
- [ ] Admin list of profiles
- [ ] Admin editing other profiles
- [x] Admin edit event
- [x] Admin delete event
- [x] Create department
- [x] Edit department
- [x] Delete department
- [x] Create shift
- [x] Edit shift
- [x] Delete shift
- [ ] About page


## Shift Availability Table
- [x] Table to display departments by day
- [ ] Automatically create slots when a shift is created / edited
- [ ] Create custom validation rule for time fields (12 hour + 24 hour)
- [ ] Include shifts / hours in event table
- [ ] Javascript to sign up for slots


## Relationships
- [x] Relationship between events and departments
- [x] Relationship between departments and shifts
- [ ] Relationship between shifts and slots
- [ ] Relationship between slots and users


## Event Triggers
- [ ] User Registered
- [ ] Event Created
- [ ] Event Edited
- [ ] Event Deleted
- [ ] Department Created
- [ ] Department Deleted
- [ ] Shift Created
- [ ] Shift Deleted
- [ ] Shift Edited
- [ ] Slot Taken
- [ ] Slot Released


## Event Handlers
- [ ] Send user email when user is registered
- [ ] Send admin email when user is registered
- [ ] Notify users on an event page when the event is changed
- [ ] Automatically display taken slots


## Misc
- [x] Prevent non-authed users from viewing events
- [ ] Footer
- [x] Look into simplifying shift -> event relationships 
- [ ] Restrict editing event IDs when editing departments
- [x] Set up inheritance for form field partials
