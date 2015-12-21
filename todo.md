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
 - [x] Department name
 - [x] Description
 - [x] Allowed user groups
 - [x] Event ID
 
- Shifts
 - [x] Start
 - [x] End
 - [x] Duration
 - [x] Allowed user groups
 - [x] Department ID

- Slots
 - [x] Start
 - [x] End
 - [x] Shift ID
 - [x] User ID

- User Data
 - [x] Burner Name
 - [x] Real Name
 - [x] Birthday
 - [x] User ID

- User Uploads
 - [x] Name
 - [x] Description
 - [x] File
 - [x] Notes
 - [x] Status
 - [x] User ID
 - [x] Admin ID


## Pages
- [x] Admin edit event
- [x] Admin delete event
- [x] Create department
- [x] Edit department
- [x] Delete department
- [x] Create shift
- [x] Edit shift
- [x] Delete shift
- [x] Viewing your own profile
- [x] Editing your own profile
- [x] Viewing list of shifts you've signed up for
- [x] Profile page to upload files
- [-] Admin list of profiles
- [-] Admin list of pending uploads
- [-] Admin editing other profiles
- [-] About page


## Shift Availability Table
- [x] Table to display departments by day
- [x] Automatically create slots when a shift is created / edited
- [x] Create custom validation rule for time fields (12 hour + 24 hour)
- [x] Remove separate grid page
- [x] Only display shifts and departments on the days they occur
- [x] Link slots to description page with times and a button to sign up
- [x] Add option to cancel your volunteer shift after signing up
- [x] Display open / taken slots
- [x] Javascript to position the times grid
- [x] Javascript to resize slots based on duration
- [x] Trigger time hover based on width instead of elmentFromPoint
- [x] Display burner name if available


## Relationships
- [x] Relationship between events and departments
- [x] Relationship between departments and shifts
- [x] Relationship between shifts and slots
- [x] Relationship between slots and users
- [x] Relationship between users and user data
- [x] Relationship between users and user uploads
- [x] Relationship between user uploads and admins


## Defined Events
- [x] User Registered
- [x] Event Changed
- [x] Slot Changed
- [x] File Uploaded
- [x] File Changed


## Event Triggers
- [x] When user is registered
- [x] When event is edited or deleted
- [x] When a department is created, edited, or deleted
- [x] When a shift is created, edited, or deleted
- [x] When a slot is taken or released
- [x] When a file is uploaded
- [-] When a file is approved or denied


## Event Handlers
- [x] Send user email when user is registered
- [x] Send admin email when user is registered
- [x] Notify users on an event page when the event is changed
- [x] Automatically display taken slots
- [x] Send admin email when a file is uploaded
- [-] Send a user email when a file is approved or denied


## Misc
- [x] Prevent non-authed users from viewing events
- [x] Look into simplifying shift -> event relationships 
- [x] Set up inheritance for form field partials
- [x] Prevent signing up for shifts after events have passed
- [x] Restrict signing up for slots based user role
- [x] Prevent users from uploading more than 3 files at a time
- [-] Restrict editing event IDs when editing departments
- [ ] Mobile nav menu
- [ ] Footer

