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
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Speedtest</title>
  </head>
  <body>
    <h1>Speedtest</h1>
    <div id="content">
      <p><input id="testButton" type="button" value="Start testing"></p>
      <div id="speedzone">
		<strong><span>Downstream</span></strong>
        <div id="download">
          <p>
            <strong>Current speed</strong> : <span>-</span> KB/s<br />
            <strong>Minimum speed</strong> : <span>-</span> KB/s<br />
            <strong>Maximum speed</strong> : <span>-</span> KB/s
          </p>
        </div>
		<strong><span>Upstream</span></strong>
        <div id="upload">
          <p>
            <strong>Current speed</strong> : <span>-</span> KB/s<br />
            <strong>Minimum speed</strong> : <span>-</span> KB/s<br />
            <strong>Maximum speed</strong> : <span>-</span> KB/s
          </p>
        </div>
      </div>
      <p id="complete" style="visibility: hidden;">Test completed!</p>
    </div>
	<!-- js at bottom for faster loading, discovered this with new relic -->
    <script src="./speedtest.js"></script>
    <script> 
      (function() {
        var $ = function(a){ return document.querySelector(a) },
            $$ = function(a){ return document.querySelectorAll(a) };
        var settings = {        
          download: {
            onprogress: function(speed, min, max) {
              $$('#download span')[0].innerHTML = speed;
              $$('#download span')[1].innerHTML = min;
              $$('#download span')[2].innerHTML = max;
            },
            onload: function(speed, min, max) {
              $$('#download strong')[0].innerHTML = 'Average speed';
              $$('#download span')[0].innerHTML = speed;
              $$('#download span')[1].innerHTML = min;
              $$('#download span')[2].innerHTML = max;
            }
          },
          upload: {
            onprogress: function(speed, min, max) {
              $$('#upload span')[0].innerHTML = speed;
              $$('#upload span')[1].innerHTML = min;
              $$('#upload span')[2].innerHTML = max;
            },
            onload: function(speed, min, max) {
              $$('#upload strong')[0].innerHTML = 'Average speed';
              $$('#upload span')[0].innerHTML = speed;
              $$('#upload span')[1].innerHTML = min;
              $$('#upload span')[2].innerHTML = max;
              $('#testButton').disabled = false;
              $('#complete').style.display = 'block';
            }
          }
        };
        var STInstance = new SpeedTest(settings);
        $('#testButton').onclick = function() {         
          $('#download strong').innerHTML = 'Current speed';
          $('#upload strong').innerHTML = 'Current speed';
          $$('#upload span')[0].innerHTML = '-';
          $$('#upload span')[1].innerHTML = '-';
          $$('#upload span')[2].innerHTML = '-';
          $('#testButton').disabled = true;
          $('#complete').style.display = 'none';
          STInstance.startRequest(true, true);
        };
      })();
    </script>
  </body>
</html>