=== Boss for LearnDash ===
Contributors: buddyboss
Requires at least: 3.8
Tested up to: 5.2.1
Stable tag: 1.3.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Boss for LearnDash integrates the LearnDash LMS with the Boss theme.

== Description ==

Make LearnDash beautiful on BuddyPress with our Boss theme integration.

Just activate the plugin, and all of your LearnDash content will match the Boss theme.

== Installation ==

1. Make sure BuddyPress, LearnDash, and Boss theme are activated.
2. Visit 'Plugins > Add New'
3. Click 'Upload Plugin'
4. Upload the file 'boss-learndash.zip'
5. Activate Boss for LearnDash from your Plugins page.

== Changelog ==



= 1.3.4 =
* Fix - Check for course price type if free then add user to access list
* Fix - Updated close icon of video preview
* Fix - Course status fix on group course single page
* Fix - Fix course currency type on course grid and course single
* Fix - Removed selected class when restart quiz
* Fix - Course navigation widget fixes
* Fix - Removed course navigation widget from bp-learndash
* Fix - Mixed content error in console fix for placehold.it images
* Fix - Course teacher private message textbox  auto clean after message sent
* Fix - Contact course teacher scrolling fix for mobile layout
* Fix - Course grid leaves blank space
* Fix - Lessons pagination on shortcodes page
* Fix - Quiz radio button styling
* Fix - Removed BB course progress widget.(Using LD Course Progress Bar widget instead of BB Course Progress widget)
* Fix - Styled LD course progress bar widget
* Fix - Currency symbol fix on course page
* Fix - Lesson and topic placeholder image fix on activity page
* Fix - Lesson pagination fix
* Fix - Improved select box style 
* Fix - Style back to lesson button on topics page
* Fix - Elements styling on Course Activity page
* Fix - Quiz tab appearing differently in sidebar
* Fix - Compatibility issue with LeanDash 3.0 - Legacy Templates

= 1.3.3 =
* Fix - Pagination fixed on archive, widgets and shortcodes
* Fix - PHP error logs fixed (undefined variables & array values)


= 1.3.2 =
* Fix - Internal server error on single course page
* Fix - User edit admin screen templates fix for course progress
* Dev - Added filter exclude_free_and_open_courses to remove open and free courses from My Courses

= 1.3.1 =
* Fix - Course Navigation widget style improvement in pagination
* Fix - ld course info widget style improvement
* Fix - "Send Invitation" link is visible for the users who do not have permission to send the invitations
* Fix - Fontawesome 5.0 upgrade
* Fix -  The topics label on single course page is not changing on course page from Learndash settings
* Fix - Course description typography issue fix
* Fix - Style glitch between course excerpt and SEE MORE button fixed
* Fix - Removed unused Learndash Courses sidebar
* Fix - Upload Assignment Box layout is broken for topic pages
* Fix - String in the learndash.js file is not translatable
* Fix - My Achievement widget style improvement
* Fix - Course Short Description is not showing when Boss for LD active
* Fix - ld_topic_list displaying courses instead of lessons
* Fix - Removed legacy forum support from buddypress group single template
* Fix - Lesson Topics string is not translatable
* Fix - RTL support added
* Fix - Lesson custom button text not working
* Fix - Profile pagination fix
* Fix - Course categories dropdown style fix on All Courses page
* Fix - Course category not displaying if we remove WP category
* Fix - Display course tags and category on a group course page
* Fix - Display course tags on a single course page
* Fix - Display learndash course category on single course page

= 1.3.0 =
* Tweak - Added boss_learndash_vars filter to override javascript vars
* Tweak - Replaced use of mb_strimwidth with wp_trim_words
* Fix - Allow to override BuddyPress for LearnDash single group template file
* Fix - Fixed Course list and Lesson list style glitch
* Fix - Added pagination support on Course and Lesson list, Profile output and Single Course page

= 1.2.9 =
* Tweak - Shorten course description string up to 60 characters in course grid
* Tweak - Added logic to load more Course participant asynchronously in Course Participant widget
* Tweak - Remove hardcoded Course Teacher widget from single course screen
* Tweak - Template updated course_content_shortcode.php, topic.php, quiz.php,  profile.php, lesson.php, course.php
* Tweak - Added "See more" and "See less" button into Course grid box
* Tweak - Allow reorder lessons and counts text in course statistic string
* Fix - Allow LearnDash Stripe integration to change button text to "Use PayPal" or "Use a Credit Card"
* Fix - Support parameter course_points_user="no" in [ld_profile] shortcode
* Fix - Translation fixes
* Fix - Fixed course menu on a mobile device
* Fix - Fixed PHP warning
* Fix - Removed margin between cursor and draggable box on matrix sorting quiz
* Fix - Added support for an additional material for lesson, topic, and quiz
* Fix - Moved course author avatar on the right side of course grid box
* Fix - Remove extra white space from course teacher's message title
* Fix - Styling glitch fix of the Upload Assignment box in Lesson
* Fix - Fixed Fatal error when Boss for Learndash plugin is the network activate
* Fix - Remove group tabs from the Course page which is connected to hidden group for the non-groups members
* Fix - Added space between course title and subject line in the message that sent from the Contact Teacher form

= 1.2.8 =
* Fix - Better mobile layout for the Learndash Profile page
* Fix - Complete quiz page style fixes
* Fix - When Course Grid plugin is not active, display course post excerpt on the course archive

= 1.2.7 =
* Fix - Add not empty check for the course material section
* Fix - course.php template update
* Fix - Course progress widget sidebar not working inside lessons and topics
* Fix - Course status progress bar is not displaying correct progress

= 1.2.6 =
* Fix - Styling issue in Learndash user profile
* Tweak - Take away ld_course_category from frontend since its private
* Fix - lesson button fixes
* Fix - Course progress widget is not works on single quiz page
* Fix - CSS style issue while viewing courses through groups if no materials and description is defined
* Fix - "In Progress" icons is not appearing under lessons list on single course page
* Enhancement - New filters added: boss_edu_course_lessons_list, boss_edu_user_assignments, boss_edu_topic_list, boss_edu_course_quiz_list
* Fix - Styling issues on Course page
* Fix - Course's BuddyPress group sidebar fix
* Fix - output entry-content wrapper in course tab only
* Fix - Course Progress Bar Design
* Enhancement - My courses template updated


= 1.2.5 =
* Enhancement – Licence Module

= 1.2.4 =
* Fix - LD2.4 - LD catagory fixes
* Fix - LD2.4 - LD Grid Enhancement

= 1.2.3 =
* Fix - LD2.4 - LD catagory/tags fixes
* Fix - LD-2.4- Course Points fixes
* Fix - LD-2.4- Certificate icon getting touch in bottom
* Fix - LD-2.4- Template path fix

= 1.2.2 =
* Fix - Course info fixes on user profile inside dashboard
* Fix - Course status bar fixes

= 1.2.1 =
* Fix - Course group link urls
* Fix - Take this course appearing twice
* Fix - Take Course button does nothing on click

= 1.2.0 =
* Fix - LearnDash v2.3 compatibility
* Fix - LearnDash v2.3 feature for users to review their quiz answers from their profile
* Fix - Styling of default LearnDash profile page is broken
* Fix - Course Participants widget is displaying a number of non-existent users (right sidebar)
* Fix - Stats column is missing from LearnDash profile
* Fix - User Course Info is missing from Users > Edit
* Fix - When changing Course Label from LearnDash settings, BuddyPanel icon is removed
* Fix - Mark Complete button not working when clicked
* Fix - When in a group template file Print Certificate is missing
* Fix - Price of course is not visible with LearnDash PayPal default for paid courses
* Fix - Code cleanup

= 1.1.2 =
* Fix - Compatibility with LearnDash v2.3
* Fix - Updated group administration template for Certification menu

= 1.1.1 =
* Fix - Add users to course participant (access) list when they complete a topic/lesson/course
* Fix - Course participant widget - do not display non-existent users
* Fix - Added UI for the new LearnDash feature Admin "Mark Complete" Capability

= 1.1.0 =
* Localization - French translations added, credits to Jean-Pierre Michaud
* Tweak - Link author from Course index to BuddyPress profile, if BuddyPress enabled
* Fix - Start course button not appearing on newly added course.
* Fix - Course Introduction video tab not appearing even added from course backend.
* Fix - If lesson has assignment feature ON then assignment uploader will not appear IF Auto Approve Assignment box is checked.
* Fix - Assignment uploading twice even though it was uploaded once
* Fix - WordPress database error
* Fix - Buttons spacing after quiz completion page.
* Fix - "My courses" Link from BuddyPress menu disappears on some pages if Boss for LearnDash is active

= 1.0.8 =
* Fix - Contact Course Teacher button width
* Fix - Distorted image height on Courses index

= 1.0.7 =
* Tweak - Various layout updates
* Tweak - Check if LearnDash is installed and activated
* Tweak - Check if Boss theme is installed and activated
* Fix - Sidebar is missing on topics pages
* Fix - Category link in group different from link in course
* Fix - LearnDash custom labels compatibility
* Fix - Lesson meta info (topics count and lesson time) added to single course
* Fix - All lessons have 1 topic assigned by default, even when none assigned
* Fix - Expand/Collapse All lesson link added
* Fix - Course tab added to single course page
* Fix - Course group extension

= 1.0.6 =
* New - Compatibility with LearnDash 2.1.8
* New - Compatibility with LearnDash 2.2 (Beta)
* New - "Course Participants" widget added
* New - "Contact Teacher" widget added
* New - Option to hide price tag of course from all courses page
* Fix - Better compatibility with Boss (Boxed) version
* Fix - Error notice when Boss theme is not active
* Fix - Added missing translation strings
* Fix - Excerpt not showing on BadgeOS achievements list
* Fix - BadgeOS achievement earners page, make user links filterable
* Fix - Conditional logic added for Send Invite button
* Fix - Check if Groups Component is active
* Fix - When creating new course it shows lesson count = 1
* Various PHP fixes
* Various CSS fixes

= 1.0.5 =
* Fix - show forum link if forum attached
* Fix - LearnDash navigation
* Fix - Quiz navigation
* Fix - Boss/Boxed layout integrations
* Fix - Make courses tab point to Group > Experiences URL
* Fix - List add-on support
* Fix - show course price in grid view
* Fix - Remove 'course' navigation if already on single course page
* Fix - Show manage navigation only if user is group admin
* Fix - Removed restrictions on display of 'Course Discussion' button in course teacher widget
* Fix - 'Free' price translation string
* Various CSS/JS fixes

= 1.0.4 =
* Switched to new Updater system
* Standard readme.txt format
* Added support for RTL languages
* Fixed translation issues
* Applied canonical fix
* Removed unused function

= 1.0.3 =
* Fixed content disappearing with latest version of LearnDash

= 1.0.2 =
* Fixed the group single template
* Fixed 404 error
* Added conditional for forum link
* Added canonical link tag for groups, so courses are primary source for SEO
* Added LearnDash course certificate button
* Various CSS fixes

= 1.0.1 =
* Fixed category link in single course page header
* Fixed activity stream image widths in Firefox
* Fixed group course discussion button logic
* Added course link in widget header

= 1.0.0 =
* Initial Release
* Styles 'LearnDash' plugin for Boss theme
* Styles 'BuddyPress for LearnDash' plugin for Boss theme
