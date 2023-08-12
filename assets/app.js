import './styles/app.scss';

const $ = require('jquery');
require('bootstrap');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();

    const form = document.getElementById('raceCreate');

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

            form.reset();
          } else {
            const error = await response.json();
            console.error('File upload error:', error.message);
          }
        } catch (error) {
          console.error('Error during CSV import:', error);
        }
      });
});