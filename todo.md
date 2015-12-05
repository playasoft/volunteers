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
 - [x] Shift
 - [x] User
 - [x] Start
 - [x] End


## Pages
- [x] Admin edit event
- [x] Admin delete event
- [x] Create department
- [x] Edit department
- [x] Delete department
- [x] Create shift
- [x] Edit shift
- [x] Delete shift
- [ ] Viewing your own profile
- [ ] Editing your own profile
- [ ] Viewing list of shifts you've signed up for
- [ ] Admin list of profiles
- [ ] Admin editing other profiles
- [ ] About page


## Shift Availability Table
- [x] Table to display departments by day
- [x] Automatically create slots when a shift is created / edited
- [x] Create custom validation rule for time fields (12 hour + 24 hour)
- [x] Remove separate grid page
- [x] Only display shifts and departments on the days they occur
- [x] Link slots to description page with times and a button to sign up
- [x] Add option to cancel your volunteer shift after signing up
- [x] Display open / taken slots
- [ ] Javascript to position the times grid
- [ ] Javascript to resize slots based on duration
- [ ] Javascript to show / hide days, departments, and shifts
- [ ] Prevent signing up for shifts after events have passed


## Relationships
- [x] Relationship between events and departments
- [x] Relationship between departments and shifts
- [x] Relationship between shifts and slots
- [x] Relationship between slots and users


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
- [x] Look into simplifying shift -> event relationships 
- [x] Set up inheritance for form field partials
- [ ] Restrict editing event IDs when editing departments
- [ ] Footer
