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
 
  // Constructor
(function() {

    function SpeedTest(settings, startNow) {
      
      // Properties
        this.requesting = false;
        this.steps; 

        this.maxTime = 8000;
        this.serverFile = 'speedtest.php';
      
      // Events
        this.download = {
          onprogress: function(){},
          onload: function(){}
        };

        this.upload = {
          onprogress: function(){},
          onload: function(){}
        };
      
      // Init
        this.applySettings(settings, this);

        startNow && startRequest(true, true); 

    }


  // Methods
    
    var fn = SpeedTest.prototype; // Object 

    fn.startRequest = function(download, twoRequests) { 
      
      if(this.requesting) return; // Prevent multiples

      // Init
        this.maxSpeed = null;
        this.minSpeed = null;
        this.readyForMaxMin = false;

        var o = this,
            xhr = new XMLHttpRequest();

        xhr.open('POST', this.serverFile);

      // Events
        var xhrProgress = function(e) {
          
          var steps = o.steps,
              progress = e.progress || e.loaded; 

          steps.push([new Date().getTime(), progress]); // check speed

          var stepsLen = steps.length - 1,
              timeDelta = steps[stepsLen][0] - steps[stepsLen - 1][0],
              progressDelta = steps[stepsLen][1] - steps[stepsLen - 1][1],
              currentSpeed = Math.ceil(progressDelta / timeDelta); // calculate cur speed
          
          if(o.readyForMaxMin) { // kips counter
            if(o.maxSpeed == null || o.maxSpeed < currentSpeed) {
              o.maxSpeed = currentSpeed;
            }

            if(o.minSpeed == null || o.minSpeed > currentSpeed) {
              o.minSpeed = currentSpeed;
            }
          }

          // User event
            if(download) {
              o.download.onprogress(currentSpeed, o.readyForMaxMin ? o.minSpeed : 0, o.readyForMaxMin ? o.maxSpeed : 0);
            } else {
              o.upload.onprogress(currentSpeed, o.readyForMaxMin ? o.minSpeed : 0, o.readyForMaxMin ? o.maxSpeed : 0);
            }

        };

        if(download) {
          xhr.onprogress = xhrProgress;
        } else {
          xhr.upload.onprogress = xhrProgress;
        }

        var xhrLoad = function() {

          o.requesting = false;

          var steps = o.steps,
              timeDelta = new Date().getTime() - steps[0][0],
              avgSpeed = Math.ceil(steps[steps.length - 1][1] / timeDelta);

          // User event
            if(download) {
              o.download.onload(avgSpeed, o.minSpeed, o.maxSpeed);
            } else {
              o.upload.onload(avgSpeed, o.minSpeed, o.maxSpeed);
            }
          
          // Second request
            twoRequests && o.startRequest(!download);

        };

        xhr.onload = xhrLoad;

      // Data
        if(!download) {

          // not sure how to store with ajax so ill use a js var

          var data = '';

          for(var i = 0 ; i < 10485760 ; i++) { // 10 Mo
            data += ' ';
          }

        }

        var form = new FormData();

        if(download) {
          form.append('d', 'd'); // "d" for download
        } else {
          form.append('u', data); // "u" for upload
        }

      // Send
        this.requesting = true;
        this.steps = [[ new Date().getTime(), 0]]; // Initializes the steps list

        this.maxTime && setTimeout(function() { // didnt work with ff
          
          if(xhr.readyState < 4) {
            xhr.abort();
            xhrLoad();
          }

        }, this.maxTime);

        setTimeout(function() { 
          o.readyForMaxMin = true;
        }, 1000);
        
        xhr.send(form);

    };


    fn.applySettings = function(settings, applyTo) {

      for(var i in settings) {
        if(typeof applyTo[i] != undefined) {
          
          if(typeof settings[i] != 'object') {
            applyTo[i] = settings[i];
          } else {
            this.applySettings(settings[i], applyTo[i]);
          }

        }
      }

    };


  // Willow says meow
    window.SpeedTest = SpeedTest;

})();