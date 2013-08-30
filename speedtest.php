<?php
/*
 * Title:        Speed Test
 * Team:         Seven-Labs
 * Developer:    Samuel Walton (samuel.walton@seven-labs.com)
 * Major Build:  07/05/2013
 * License:      MIT / GNU
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
  $filesize = 20971520; // 20 megabytes

  if(isset($_POST['d'])) {

    header('Cache-Control: no-cache');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: '. $filesize);

    for($i = 0 ; $i < $filesize ; $i++) {
      echo chr(255);
    }

  }

?>