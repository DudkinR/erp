@extends('layouts.app')
@section('content')
<div class="container position-relative">
    <h3>Оцінка ризиків</h3>

    <!-- Панель середнього ризику -->
    <div id="avgRiskPanel" 
         style="position:fixed; top:10px; right:10px; 
                background-color:rgba(0,255,0,0.3); 
                padding:10px 20px; border-radius:8px; font-weight:bold; 
                z-index:999;">
        Середній ризик: 0 (Прийнятний)
    </div>

    <!-- Форма -->
    <form id="riskForm" method="POST" action="{{ route('risks.createform') }}">
        @csrf
        <div id="risk-table" class="mt-4"></div>
        <button type="submit" class="btn btn-primary mt-3">Зберегти оцінку</button>
    </form>
</div>

<script>
// Масив небезпек
const eventsData = @json($eventsData);

// групування по work_type
function groupByWorkType(data) {
    return data.reduce((acc, ev) => {
        if (!acc[ev.work_type]) acc[ev.work_type] = [];
        acc[ev.work_type].push(ev);
        return acc;
    }, {});
}

function renderRiskTable() {
    const grouped = groupByWorkType(eventsData);
    let html = "";

    for (const [workType, events] of Object.entries(grouped)) {
        // чекбокс для групи
        html += `
        <div class="form-check mb-2">
            <input class="form-check-input group-toggle" type="checkbox" 
                   id="group_${workType}" data-group="${workType}" checked>
            <label class="form-check-label fw-bold text-info" for="group_${workType}">
                ${workType}
            </label>
        </div>`;

        html += `<table class="table table-bordered group-table" data-group="${workType}">
            <thead class="table-info">
                <tr>
                    <th>Від події та інші небезпечні фактори</th>
                    <th>Т</th>
                    <th>І</th>
                    <th>Ч</th>
                    <th>R</th>
                    <th>Категорія</th>
                </tr>
            </thead><tbody>`;

        events.forEach(ev => {
            html += `
            <tr>
                <td>
                    ${ev.name}
                    <input type="hidden" name="events[${ev.id}][name]" value="${ev.name}">
                    <input type="hidden" name="events[${ev.id}][work_type]" value="${ev.work_type}">
                </td>
                <td><input type="number" min="0" max="7" step="0.01" 
                           class="form-control risk-input" 
                           name="events[${ev.id}][severity]"
                           value="${ev.severity}" data-original="${ev.severity}"></td>
                <td><input type="number" min="0" max="7" step="0.01" 
                           class="form-control risk-input" 
                           name="events[${ev.id}][probability]"
                           value="${ev.probability}" data-original="${ev.probability}"></td>
                <td><input type="number" min="0" max="7" step="0.01" 
                           class="form-control risk-input" 
                           name="events[${ev.id}][frequency]"
                           value="${ev.frequency}" data-original="${ev.frequency}"></td>
                <td class="risk-value">0</td>
                <td class="risk-category">Прийнятний</td>
            </tr>`;
        });

        html += `</tbody></table>`;
    }

    document.getElementById('risk-table').innerHTML = html;

    // слухачі для інпутів
    document.querySelectorAll('.risk-input').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value !== this.dataset.original) {
                this.style.borderColor = 'red';
                this.style.backgroundColor = '#ffe5e5';
            } else {
                this.style.borderColor = '';
                this.style.backgroundColor = '';
            }
            recalcAll();
        });
    });

    // слухачі для чекбоксів груп
    document.querySelectorAll('.group-toggle').forEach(cb => {
        cb.addEventListener('change', function() {
            const groupName = this.dataset.group;
            const table = document.querySelector(`.group-table[data-group="${groupName}"]`);

            if (!this.checked) {
                table.style.opacity = 0.4;
                table.querySelectorAll('input').forEach(inp => inp.disabled = true);
            } else {
                table.style.opacity = 1;
                table.querySelectorAll('input').forEach(inp => inp.disabled = false);
            }

            recalcAll();
        });
    });

    recalcAll(true);
}



function animateValue(element, start, end, duration = 10000) {
    let startTime = null;

    function step(timestamp) {
        if (!startTime) startTime = timestamp;
        let progress = (timestamp - startTime) / duration;
        if (progress > 1) progress = 1;

        // плавне збільшення
        let value = start + (end - start) * progress;

        // невеликі коливання ±1
        let oscillation = Math.sin(progress * Math.PI * 4) * 0.5; 
        value = value + oscillation;

        element.textContent = value.toFixed(2);

        if (progress < 1) {
            requestAnimationFrame(step);
        } else {
            // фінальне значення
            element.textContent = end.toFixed(2);
        }
    }

    requestAnimationFrame(step);
}

function recalcAll(initial=false) {
    let totalRisk = 0;
    let count = 0;

    document.querySelectorAll('.group-table').forEach(table => {
        const groupName = table.dataset.group;
        const groupEnabled = document.querySelector(`#group_${CSS.escape(groupName)}`).checked;

        if (!groupEnabled) {
            // якщо група виключена — пропускаємо її
            table.style.opacity = 0.4;
            return;
        } else {
            table.style.opacity = 1;
        }

        table.querySelectorAll('tbody tr').forEach(row => {
            const T = parseFloat(row.querySelector('td:nth-child(2) input').value) || 0;
            const I = parseFloat(row.querySelector('td:nth-child(3) input').value) || 0;
            const C = parseFloat(row.querySelector('td:nth-child(4) input').value) || 0;
            const R = T * I * C;

            let category = '';
            let rowClass = '';
            if (R <= 7) { category = 'Прийнятний'; rowClass = ''; }
            else if (R <= 20) { category = 'Допустимий'; rowClass = 'table-warning'; }
            else { category = 'Значний/Неприйнятний'; rowClass = 'table-danger'; }

            row.className = rowClass;

            if (initial) {
                animateValue(row.querySelector('.risk-value'), 0, R, 1000);
            } else {
                row.querySelector('.risk-value').textContent = R.toFixed(2);
            }
            row.querySelector('.risk-category').textContent = category;

            totalRisk += R;
            count++;
        });
    });

    const avgRisk = count > 0 ? (totalRisk / count).toFixed(2) : 0;
    const panel = document.getElementById('avgRiskPanel');

    let color = 'rgba(0,255,0,0.3)';
    let text = 'Прийнятний';
    if (avgRisk > 7 && avgRisk <= 20) { color = 'rgba(255,255,0,0.3)'; text = 'Ризиковано'; }
    else if (avgRisk > 20 && avgRisk <= 50) { color = 'rgba(255,165,0,0.3)'; text = 'Значний'; }
    else if (avgRisk > 50) { color = 'rgba(255,0,0,0.3)'; text = 'Неприйнятний'; }

    panel.style.backgroundColor = color;
    panel.textContent = `Середній ризик: ${avgRisk} (${text})`;
}



renderRiskTable();
</script>
@endsection
