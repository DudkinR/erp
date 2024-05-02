
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function add_new_step(stage_id) {
    const succesful_step = document.getElementById('succesful_step');
    const url = "/steps";
    // Ensure csrfToken is globally available or fetch it inside this function
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const data = {
        "_token": csrfToken,
        "name": document.getElementById('new_step').value, // Changed from querySelector('input[name="new_step"]').value to document.getElementById('new_step').value to be more universally compatible
        "stages_id": stage_id,
        "description": "",
        "novisability": "1",
    };
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Success:', data);
        const all_steps = data['steps']; // Make sure the 'steps' is correct and provided by your backend
        const select = document.querySelector('select[name="steps_id[]"]');
        const option = document.createElement('option');
        option.value = data.id; // Ensure that your response object has 'id'
        option.text = data.name; // Ensure that your response object has 'name'
        select.appendChild(option); // Changed from .add() to .appendChild() to be more universally compatible
        succesful_step.innerHTML = 'Step added successfully';
        // clean 
        document.getElementById('new_step').value = '';

    })
    .catch(error => {
        console.error('Error:', error);
        succesful_step.innerHTML = 'Error adding step';
        document.getElementById('new_step').value = '';
    });
}


function add_step_to_stage(step_id, stage_id){
    const url =  "/stagestep/add_step";
    const data = {
        "_token":  csrfToken,
        "step_id": step_id,
        "stage_id":  stage_id,
    };
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data);
      //  location.reload();
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

// addControl
function addControl(step_id){
    const url =  "/controls";
    const data = {
        "_token":  csrfToken,
        "step_id": step_id,
        "name": document.getElementById('new_control').value,
        "description": "",
        "novisability": "1",
    };
    console.log(data);
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
       body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data);
       // location.reload();
    })
    .catch((error) => {
        console.log('Error:', error);
    });
  //  console.log(step_id);
  location.reload();
}

// remove_step_from_stage
function remove_step_from_stage(step_id, stage_id){
    const url =  "/stagesstep/remove_step";
    const data = {
        "_token":  csrfToken,
        "step_id": step_id,
        "stage_id": stage_id,
    };
    const element = document.getElementById('work_step_id_'+step_id); 
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data);
      //  location.reload(); 
      // remove <li id="work_step_id_{{$step->id}}">
      if (element) {
        element.parentNode.removeChild(element);
      }
      
    })
    .catch((error) => {
        console.error('Error:', error);
        if (element) {
        element.parentNode.removeChild(element);
        }
    }); 
     
}