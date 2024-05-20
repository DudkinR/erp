
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
    
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
    
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
    
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

// add_dimension_to_control
function add_dimension_to_control(control_id){
    
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const url =  "/dimensions";
    const name = document.getElementById('dimensions_new').value;
    const data = {
        "_token":  csrfToken,
        "control_id": control_id,
        "name": name,
        "description": "",
        "novisability": "1",
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
        location.reload();
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

function add_stage_to_project(stage_id,project_id,deadline ,responsible_position_id){ 
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = "/projectstage/add_stage";
    const data = {
       "_token":   csrfToken,
        "stage_id": stage_id,
        "project_id": project_id,
        "deadline" : deadline,
        "responsible_position_id": responsible_position_id,
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
           location.reload();
       })
       .catch((error) => {
           console.error('Error:', error);
       });
   }
   function findDocument() {
    //find words in docs (name, description) and show (id) in select 
    // Ранее выбраные документы  должны отображаться в списке
    var find = document.getElementById("find_document").value;
    var select = document.getElementById("document_releted");  
    var old_selected = select.selectedOptions;
    for (var i = 0; i < select.options.length; i++) {
        select.options[i].style.display = "none";
        if (docs[i].name.includes(find) || docs[i].description.includes(find)) {
            select.options[i].style.display = "block";
        }
        
    }

}
// if getElementById("image") has on page
if (document.getElementById("image")) {
    document.getElementById("image").addEventListener("change", function (event) {
        var image_preview = document.getElementById("image_preview");
        while (image_preview.firstChild) {
            image_preview.removeChild(image_preview.firstChild);
        }
        for (var i = 0; i < event.target.files.length; i++) {
            var img = document.createElement("img");
            img.src = URL.createObjectURL(event.target.files[i]);
            img.style.maxWidth = "300px";
            img.style.maxHeight = "300px";
            image_preview.appendChild(img);
        }
    });
}

function addDraftDocument(){
    const draft_document_name = document.getElementById("find_document").value;
    //ajax metod post to  store docs
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = "/apistoredocs";
    const data = {
        "_token": csrfToken,
        "name": draft_document_name,
        "description": "",
        "status": 0,
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
const abc_uk=['а','б','в','г','ґ','д','е','є','ж','з','и','і','ї','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ь','ю','я','А','Б','В','Г','Ґ','Д','Е','Є','Ж','З','И','І','Ї','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ь','Ю','Я','1','2','3','4','5','6','7','8','9','0',' ','/',':'];
const abc_en=['a','b','v','h','g','d','e','ye','zh','z','y','i','yi','y','k','l','m','n','o','p','r','s','t','u','f','kh','ts','ch','sh','shch','`','yu','ya','A','B','V','H','G','D','E','Ye','Zh','Z','Y','I','Yi','Y','K','L','M','N','O','P','R','S','T','U','F','Kh','Ts','Ch','Sh','Shch','`','Yu','Ya','1','2','3','4','5','6','7','8','9','0','-','-','-'];
const bad_symbols = [ '`', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '=', '+', '[', ']', '{', '}', ';',  '"', "'", ',', '<', '>', '.',  '?', '\\', '|'];
if(document.getElementById('name') && document.getElementById('slug') && document.getElementById('slug').value ==''){
    document.getElementById('name').addEventListener('blur', function(){
        var name = document.getElementById('name').value;
        var name_words = name.split(/\s+/); // Разделяем строку по пробелам, но сохраняем пробелы
        var slug_words = [];
        var slug_text = '';
        for (var i = 0; i < name_words.length; i++) {
            var word = name_words[i];
            for (var j = 0; j < word.length; j++) {
                var symbol = word[j];
                if (abc_uk.includes(symbol)) {
                    var index = abc_uk.indexOf(symbol);
                    slug_words.push(abc_en[index]);
                } else if (abc_en.includes(symbol)) {
                    slug_words.push(symbol);
                }
            }
            if (slug_words.length > 0) {
                slug_text += slug_words.join('') + '-'; // Присоединяем слова без тире
                slug_words = []; // Очищаем массив для следующего слова
            }
            if (slug_text.length > 15) {
                break;
            }
        }
        // Убираем последнее тире, если оно лишнее
        if (slug_text.endsWith('-')) {
            slug_text = slug_text.slice(0, -1);
        }
        document.getElementById('slug').value = slug_text;
    });
}
// при изменение любого select и input  отсылать запрос на сервер запоминать в сессии  имя и значение 
// и при загрузке страницы заполнять поля select и input
var inputs = document.querySelectorAll( 'input, select' );
for (var i = 0; i < inputs.length; i++) {
    inputs[i].addEventListener('change', function(event) {
        var name = event.target.name ? event.target.name : event.target.id;
        var value = event.target.value;
        console.log(name, value);
        var data = {
            ns: name,
            vs: value
        };
        fetch('/ss?'+new URLSearchParams(data), {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    });
}
