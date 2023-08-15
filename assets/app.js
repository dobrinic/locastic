import './styles/app.scss';

const $ = require('jquery');
require('bootstrap');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();

    const form = document.getElementById('raceCreate');
    const showRacesButton = document.getElementById('show-races');
    const raceJumbotron = document.querySelector(".races-jumbotron");
    const runnerJumbotron = document.querySelector(".runner-jumbotron");

    showRacesButton.addEventListener('click', function(){
      raceJumbotron.classList.remove('d-none');
    })

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const formData = new FormData(form);
  
        try {
          const response = await fetch(form.action, {
            method: form.method,
            body: formData
          });
  
          if (response.ok) {
            const data = await response.json();
            console.log('File uploaded successfully:', data.message);

            raceJumbotron.classList.remove('d-none');
            form.reset();
            fetchRaces();
          } else {
            const error = await response.json();
            console.error('File upload error:', error.message);
          }
        } catch (error) {
          console.error('Error during CSV import:', error);
        }
      });


      const raceTableBody = document.getElementById("race-table-body");
      const runnerTableBody = document.getElementById("runner-table-body");
      const raceSearchInput = document.getElementById("raceSearch");

      function fetchRaces(orderBy, direction, search) {
        let racesUrl = raceTableBody.dataset.path + `${orderBy ? `?order[${orderBy}]=${direction}` : ''}`;
        if (search) {
          racesUrl += `${orderBy ? '&' : '?'}title=${search}`;
        }
  
        raceTableBody.innerHTML = '';

        fetch(racesUrl)
          .then(response => response.json())
          .then(data => {
            data["hydra:member"].forEach(race => {
              const row = document.createElement("tr");
              row.innerHTML = `
                <td>${race.title}</td>
                <td>${race.date}</td>
                <td>${race.averageTimeLong}</td>
                <td>${race.averageTimeMedium}</td>
                <td><button class="btn btn-sm btn-success fetch-runners-button" data-race="${race["@id"]}">Fetch Runners</button></td>
              `;
              raceTableBody.appendChild(row);
            });
          })
          .catch(error => console.error("Error fetching races:", error));
        }

        function fetchRunners(selectedRaceId, orderBy, direction, search, column) {
          let runnersUrl = selectedRaceId + `/runners${orderBy ? `?order[${orderBy}]=${direction}` : ''}`;
          if (search) {
            runnersUrl += `${orderBy ? '&' : '?'}${column}=${search}`;
          }
        
          runnerTableBody.innerHTML = "";
          runnerTableBody.dataset.raceid = selectedRaceId;
  
          fetch(runnersUrl)
            .then(response => response.json())
            .then(race => {
              race["hydra:member"].forEach(runner => {
                const row = document.createElement("tr");
                row.innerHTML = `
                  <td><input type="text" class="form-control" name="name" value="${runner.name}"</td>
                  <td><input type="text" class="form-control" name="finishTime" value="${runner.finishTime}"</td>
                  <td><input type="text" class="form-control" name="distance" value="${runner.distance}"</td>
                  <td><input type="text" class="form-control" name="ageCategory" value="${runner.ageCategory}"</td>
                  <td>${runner.placement}</td>
                  <td>${runner.agePlacement}</td>
                  <td><button class="btn btn-sm btn-success edit-result-button" data-runner="${runner["@id"]}">Edit Result</button></td>
                `;
                runnerTableBody.appendChild(row);
              });
            })
            .catch(error => console.error("Error fetching runners:", error));
        }

        document.addEventListener("click", function(event) {
          if (event.target.classList.contains("fetch-runners-button")) {
            runnerJumbotron.classList.remove('d-none');
            const selectedRaceId = event.target.getAttribute("data-race");
            fetchRunners(selectedRaceId);
          }
        });

        document.addEventListener("click", function(event) {
          if (event.target.classList.contains("sortable")) {
            const column = event.target.getAttribute("data-column");
            const direction = event.target.getAttribute("data-dir");
            let dir = 'asc';
            event.target.dataset.dir = 'asc'
            
            if (direction === 'asc') {
              event.target.dataset.dir = 'desc'
              dir = 'desc'
            }

            fetchRunners(runnerTableBody.dataset.raceid, column, dir);
          }
        });

        document.addEventListener("click", function(event) {
          if (event.target.classList.contains("sortable-race")) {
            const column = event.target.getAttribute("data-column");
            const direction = event.target.getAttribute("data-dir");
            let dir = 'asc';
            event.target.dataset.dir = 'asc'
            
            if (direction === 'asc') {
              event.target.dataset.dir = 'desc'
              dir = 'desc'
            }

            fetchRaces(column, dir);
          }
        });

        raceSearchInput.addEventListener("input", function() {
          const searchName = raceSearchInput.value.trim();
          fetchRaces(null, null, searchName);
        });

        $('.resultsSearch').on("input", function() {
          const searchTerm = this.value.trim();
          const column = this.dataset.column;
          fetchRunners(runnerTableBody.dataset.raceid, null, null, searchTerm, column);
        });

        $(document).on("click", "#runner-table-body .edit-result-button", function() {
          const resource = this.dataset.runner;
          const $button = $(this);
          const $inputs = $button.closest('tr').find('input');
          const runnerData = {};

          $inputs.each(function(i,input) {
            runnerData[input.name] = input.value;
          })
          runnerData['race'] = runnerTableBody.dataset.raceid;

          fetch(resource, {
            method: "PATCH",
            headers: {
              "Content-Type": "application/merge-patch+json",
            },
            body: JSON.stringify(runnerData)
          })
          .then(response => response.json())
          .then(data => {
            console.log(data);
          })
          .catch(error => console.error("Error fetching races:", error));
        });

        fetchRaces();
});