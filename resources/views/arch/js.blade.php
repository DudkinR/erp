<script>
//   return view('arch.edit', compact('document', 'stageTypes', 'objectTypes', 'packages', 'package', 'docTypes', 'buildings', 'docs', 'develops','Contractors', 'archiveTypes', 'parent_type_doc', 'parent_type_Developer', 'parent_type_Contractor', 'parent_type_object', 'parent_type_arhive'));



            const packages = @json($packages);
            const docs = @json($docTypes);
            const objects = @json($objectTypes);
            console.log(objects);
            const develops = @json($develops);
            const adocs = @json($docs);
            const contractors = @json($Contractors);
            const archiveTypes = @json($archiveTypes);

            const parent_type_doc = @json($parent_type_doc);
            const parent_type_Developer = @json($parent_type_Developer);
            const parent_type_Contractor = @json($parent_type_Contractor);
            const parent_type_object = @json($parent_type_object);
            const parent_type_arhive = @json($parent_type_arhive);

            const packageIdInput = document.getElementById('package_id');
            const packageNationalNameInput = document.getElementById('package_national_name');
            const packageForeignNameInput = document.getElementById('package_foreign_name');

            document.getElementById('package_search').addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase().trim();

                const resultsDiv = document.getElementById('package_results');
                resultsDiv.innerHTML = '';

                if (searchValue.length < 2) return;

                const results = packages.filter(pkg => 
                    (pkg.national_name && pkg.national_name.toLowerCase().includes(searchValue)) ||
                    (pkg.foreign_name && pkg.foreign_name.toLowerCase().includes(searchValue))
                ).slice(0, 10);

                if (results.length === 0) {
                    resultsDiv.innerHTML = '<p class="text-muted">Нічого не знайдено</p>';
                    return;
                }

                results.forEach(pkg => {
                    const a = document.createElement('a');
                    a.href = "#";
                    a.className = "d-block mb-2";
                    const mainName = pkg.national_name && pkg.national_name.trim() !== '' ? pkg.national_name : (pkg.foreign_name ?? '');
                    const secondName = (pkg.national_name && pkg.foreign_name) ? ` (${pkg.foreign_name})` : '';
                    a.textContent = `${mainName}${secondName} - #${pkg.id}`;
                    a.onclick = e => {
                        e.preventDefault();
                        packageIdInput.value = pkg.id;
                        packageNationalNameInput.value = pkg.national_name || '';
                        packageForeignNameInput.value = pkg.foreign_name || '';
                        document.getElementById('selected_package').textContent = a.textContent;
                        bootstrap.Modal.getInstance(document.getElementById('packageModal')).hide();
                    };
                    resultsDiv.appendChild(a);
                });
            });

            // Створення нового пакета
            function createPackage() {
                const nationalName = document.getElementById('create_package_national').value.trim();
                const foreignName = document.getElementById('create_package_foreign').value.trim();

                if (!nationalName && !foreignName) {
                    alert('Будь ласка, введіть хоча б одну назву пакета.');
                    return;
                }

                // Зберігаємо у hidden поля
                packageIdInput.value = 0; // новий пакет
                packageNationalNameInput.value = nationalName;
                packageForeignNameInput.value = foreignName;
                document.getElementById('selected_package').textContent = `Новий пакет: ${nationalName || foreignName}`;

                bootstrap.Modal.getInstance(document.getElementById('packageModal')).hide();
            }
    function initKorAutocomplete() {
        const input = document.getElementById('kor_input');
        const suggestionsDiv = document.getElementById('kor_suggestions');
        input.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            suggestionsDiv.innerHTML = '';
            if (query.length < 1) return;
            const results = kors.filter(kor =>
                kor.name.toLowerCase().includes(query) ||
                (kor.abv && kor.abv.toLowerCase().includes(query))
            ).slice(0, 8);
            if (results.length === 0) return;
            results.forEach(kor => {
                const item = document.createElement('button');
                item.type = "button";
                item.className = "list-group-item list-group-item-action";
                item.textContent = kor.name;
                item.onclick = () => {
                    input.value = kor.name;
                    suggestionsDiv.innerHTML = ''; // сховати підказки
                };
                suggestionsDiv.appendChild(item);
            });
        });
        // Ховаємо підказки при кліку поза полем
        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.innerHTML = '';
            }
        });
    }
    function initDevelopAutocomplete() {
        const input = document.getElementById('develop_input');
        const suggestionsDiv = document.getElementById('develop_suggestions');
        input.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            suggestionsDiv.innerHTML = '';
            if (query.length < 1) return;
            const results = develops.filter(dev => 
                dev.name.toLowerCase().includes(query) || 
                (dev.abv && dev.abv.toLowerCase().includes(query))
            ).slice(0, 8);
            if (results.length === 0) return;
            results.forEach(dev => {
                const item = document.createElement('button');
                item.type = "button";
                item.className = "list-group-item list-group-item-action";
                item.textContent = dev.name;
                item.onclick = () => {
                    input.value = dev.name;
                    suggestionsDiv.innerHTML = ''; // сховати підказки
                };
                suggestionsDiv.appendChild(item);
            });
        });
        // Ховаємо підказки при кліку поза полем
        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.innerHTML = '';
            }
        });
    }
    function initObjectAutocomplete() {
        const input = document.getElementById('object_input');
        const suggestionsDiv = document.getElementById('object_suggestions');
        console.log('Object autocomplete initialized');
        input.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            suggestionsDiv.innerHTML = '';
            if (query.length < 1) return;
            const results = objects.filter(obj =>
                (obj.name && obj.name.toLowerCase().includes(query)) ||
                (obj.abv && obj.abv.toLowerCase().includes(query)) 
            ).slice(0, 8);
            if (results.length === 0) return;
            results.forEach(obj => {
                const item = document.createElement('button');
                item.type = "button";
                item.className = "list-group-item list-group-item-action";
                // показуємо і назву, і абревіатуру, і IDBuilding
                item.textContent = `${obj.name} `;
                item.onclick = () => {
                    input.value = obj.name; // або можна вставляти `${obj.name} (${obj.abv})`
                    suggestionsDiv.innerHTML = ''; // сховати підказки
                };
                suggestionsDiv.appendChild(item);
            });
        });

        // Ховаємо підказки при кліку поза полем
        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.innerHTML = '';
            }
        });
    }

    function initDocAutocomplete() {
        const foreignInput = document.getElementById('foreign_name');
        const nationalInput = document.getElementById('national_name');
        const suggestionsDiv = document.getElementById('doc_suggestions');

        function showSuggestions(query) {
            suggestionsDiv.innerHTML = '';
            if (query.length < 1) return;

            const results = docs.filter(doc =>
                (doc.foreign_name && doc.foreign_name.toLowerCase().includes(query)) ||
                (doc.national_name && doc.national_name.toLowerCase().includes(query))
            ).slice(0, 8);

            if (results.length === 0) return;

            results.forEach(doc => {
                const item = document.createElement('button');
                item.type = "button";
                item.className = "list-group-item list-group-item-action";
                item.textContent = `${doc.foreign_name || ''} ${doc.national_name ? " / " + doc.national_name : ''}`;

                item.onclick = () => {
                    foreignInput.value = doc.foreign_name || '';
                    nationalInput.value = doc.national_name || '';
                    suggestionsDiv.innerHTML = ''; // сховати
                };
                suggestionsDiv.appendChild(item);
            });
        }

        foreignInput.addEventListener('input', function () {
            showSuggestions(this.value.toLowerCase().trim());
        });

        nationalInput.addEventListener('input', function () {
            showSuggestions(this.value.toLowerCase().trim());
        });

        // закриття при кліку поза
        document.addEventListener('click', function (e) {
            if (!foreignInput.contains(e.target) &&
                !nationalInput.contains(e.target) &&
                !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.innerHTML = '';
            }
        });
    }

    const statusSelect = document.getElementById('status_select');
    const searchBlock = document.getElementById('replaced_search_block');
    const searchInput = document.getElementById('doc_search');
    const resultsDiv = document.getElementById('doc_results');
    const replacedId = document.getElementById('replaced_id');
    const replacedLabel = document.getElementById('replaced_label');

    // показуємо пошук лише якщо статус = replaced
    statusSelect.addEventListener('change', function () {
        if (this.value === 'replaced') {
            searchBlock.style.display = 'block';
        } else {
            searchBlock.style.display = 'none';
            replacedId.value = '';
            replacedLabel.textContent = '';
            resultsDiv.innerHTML = '';
        }
    });

    // пошук документів
    searchInput.addEventListener('keyup', function () {
        const query = this.value.toLowerCase().trim();
        resultsDiv.innerHTML = '';

        if (query.length < 2) return;

        const matches = adocs.filter(d =>
            (d.code && d.code.toLowerCase().includes(query)) ||
            (d.inventory && d.inventory.toLowerCase().includes(query))
        );

        matches.forEach(doc => {
            const div = document.createElement('div');
            div.textContent = `${doc.id}: ${doc.foreign_name || doc.national_name || doc.code}`;
            div.style.padding = '5px';
            div.style.cursor = 'pointer';
            div.addEventListener('click', () => {
                replacedId.value = doc.id;
                replacedLabel.textContent = `Вибрано: ${doc.foreign_name || doc.national_name || doc.code} (ID: ${doc.id}) (інв. №: ${doc.inventory}) (арх. №: ${doc.archive_number}) (шифр:   ${doc.code})`;
                resultsDiv.innerHTML = '';
                searchInput.value = '';
            });
            resultsDiv.appendChild(div);
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
  // Знаходимо всі відповідні input
  const inputs = document.querySelectorAll('input');
  inputs.forEach(input => {
    // Перевірка при завантаженні
    if (input.value.trim() === "") {
      input.style.backgroundColor = "yellow";
    }

    // Перевірка при зміні значення
    input.addEventListener("input", function () {
      if (input.value.trim() === "") {
        input.style.backgroundColor = "yellow";
      } else {
        input.style.backgroundColor = ""; // Повертаємо стандартний фон
      }
    });
  });
});

   function set_storage_location() {
    const archiveType = document.querySelector('input[name="archive_type"]:checked');
    const shelf = document.querySelector('input[name="shelf"]').value.trim();
    const cabinet = document.querySelector('input[name="cabinet"]').value.trim();
    const box = document.querySelector('input[name="box"]').value.trim();
    const folder = document.querySelector('input[name="folder"]').value.trim();

    let locationParts = [];

    // Якщо вибрано archiveType – його значення, якщо ні – "__"
    locationParts.push(archiveType ? archiveType.value : "__");

    // Додаємо інші поля з "__", якщо вони пусті
    locationParts.push(shelf || "__");
    locationParts.push(cabinet || "__");
    locationParts.push(box || "__");
    locationParts.push(folder || "__");

    const locationField = document.querySelector('input[name="location"]');
    locationField.value = locationParts.join('_');
}

</script>