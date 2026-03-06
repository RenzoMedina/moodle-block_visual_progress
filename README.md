# Visual Progress #

In Moodle, students do not know where they are in the course unless they manually review each activity. This block gives them immediate visibility of their progress and motivates them to continue.

## Features ##

- Display the **percentage of activities completed** out of the total number of activities with completion tracking enabled.
- Represent that percentage with a **visual progress bar**.
- Display an **encouraging message** based on the range:
  - 0–40% → “You're just getting started, you can do it!”
  - 41–70% → “Good pace, keep it up!”
  - 71–99% → “You're almost there!”
  - 100% → “Course completed! 🎉”
- The administrator can enable or disable **teacher view mode** from the block settings.

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/blocks/visual_progress

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## License ##

2026 Renzo Medina <medinast30@gmail.com>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
