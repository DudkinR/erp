@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white text-center">
                        <h4 class="mb-0">📊 Калькулятор ефективності та потенціалу наради</h4>
                    </div>
                    <div class="card-body">
                        
                       <form id="meetingForm" class="row g-3">
    
                        <!-- БЛОК ПАРАМЕТРІВ НАРАДИ -->
                        <div class="col-12">
                            <div class="p-3 bg-light rounded border">
                                <h5 class="text-secondary border-bottom pb-2 mb-3">📋 Основна інформація</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="meetingName" class="form-label" >Назва наради</label>
                                        <input type="text" id="meetingName" class="form-control" value="" placeholder="Введіть назву...">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="meetingFormat" class="form-label">Формат та місце проведення</label>
                                        <select id="meetingFormat" class="form-select">
                                            <option value="1.0" selected>Онлайн (Jisti) [К = 1.0]</option>
                                            <option value="1.2">Офлайн (Кабінет/Мітинг-рум) [К = 1.2]</option>
                                            <option value="1.5">По місцю проведення робіт (Об'єкт/Цех) [К = 1.5]</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- БЛОК 1: ЧАС ТА ВАРТІСТЬ -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h5 class="text-primary border-bottom pb-2 mb-3">⏰ Час та базові параметри</h5>
                                
                                <div class="mb-3">
                                    <label for="startTime" class="form-label">Початок наради</label>
                                    <input type="time" id="startTime" class="form-control" value="08:00">
                                </div>
                                <div class="mb-3">
                                    <label for="realstartTime" class="form-label">Початок наради реальний</label>
                                    <input type="time" id="realstartTime" class="form-control" value="08:00">
                                </div>
                                <div class="mb-3">
                                    <label for="ajendaTime" class="form-label">Плануємий час (мінутах) </label>
                                    <input type="number" id="ajendaTime" class="form-control" value="30"  min="1">
                                </div>
                                <div class="mb-3">
                                    <label for="endTime" class="form-label">Кінець наради</label>
                                    <input type="time" id="endTime" class="form-control" value="08:30">
                                </div>

                                <div class="mb-0">
                                    <label for="hourlyWage" class="form-label">Середня з/п за годину (грн)</label>
                                    <input type="number" id="hourlyWage" class="form-control" value="400" min="0">
                                </div>
                            </div>
                        </div>

                        <!-- БЛОК 2: УЧАСНИКИ ТА КОЕФІЦІЄНТИ -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h5 class="text-success border-bottom pb-2 mb-3">👥 Склад учасників</h5>
                                
                                <div class="mb-3">
                                    <label for="countTop" class="form-label">Вища ланка (К = 2.0)</label>
                                    <input type="number" id="countTop" class="form-control" value="0" min="0">
                                </div>

                                <div class="mb-3">
                                    <label for="countMiddle" class="form-label">Начальники / Зами підрозділів (К = 1.5)</label>
                                    <input type="number" id="countMiddle" class="form-control" value="0" min="0">
                                </div>

                                <div class="mb-0">
                                    <label for="countRegular" class="form-label">Інші співробітники (К = 1.0)</label>
                                    <input type="number" id="countRegular" class="form-control" value="0" min="0">
                                </div>
                                <div class="mb-0">
                                    <label for="countlate" class="form-label">Кількість хто запизнився</label>
                                    <input type="number" id="countlate" class="form-control" value="0" min="0">
                                </div>
                                <div class="mb-0">
                                    <label for="countleave" class="form-label">Кількість хто відвлікався / уходив</label>
                                    <input type="number" id="countleave" class="form-control" value="0" min="0">
                                </div>
                                
                                <div class="mb-0">
                                    <label for="countpass" class="form-label">Кількість хто не прийшов (обов'язково запрошений)</label>
                                    <input type="number" id="countpass" class="form-control" value="0" min="0">
                                </div>
                            </div>
                        </div>

                        <!-- БЛОК 3: ПИТАННЯ ТА ГОТОВНІСТЬ -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h5 class="text-warning border-bottom pb-2 mb-3">❓ Питання та підготовка</h5>
                                
                                <div class="mb-3">
                                    <label for="questionsBefore" class="form-label">Питань поставлено ДО наради</label>
                                    <input type="number" id="questionsBefore" class="form-control" value="1" min="0">
                                </div>

                                <div class="mb-3">
                                    <label for="readiness" class="form-label">Готовність відповідати (%)</label>
                                    <input type="number" id="readiness" class="form-control" value="100" min="0" max="100">
                                </div>

                                <div class="mb-0">
                                    <label for="questionsNew" class="form-label">Нових питань (виникло в процесі)</label>
                                    <input type="number" id="questionsNew" class="form-control" value="0" min="0">
                                </div>
                            </div>
                        </div>

                        <!-- БЛОК 4: РЕЗУЛЬТАТИВНІСТЬ -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100">
                                <h5 class="text-danger border-bottom pb-2 mb-3">🎯 Прийняті рішення</h5>
                                
                                <div class="mb-3">
                                    <label for="decisionsAdopted" class="form-label">Прийнято рішень</label>
                                    <input type="number" id="decisionsAdopted" class="form-control" value="1" min="0">
                                </div>

                                <div class="mb-3">
                                    <label for="decisionsReviewed" class="form-label">Переглянуто рішень</label>
                                    <input type="number" id="decisionsReviewed" class="form-control" value="0" min="0">
                                </div>

                                <div class="mb-0">
                                    <label for="decisionsDelayed" class="form-label">Здвинуто термінів виконання</label>
                                    <input type="number" id="decisionsDelayed" class="form-control" value="0" min="0">
                                </div>
                            </div>
                        </div>

                    </form>


                    </div>
                </div>

                <!-- ТАБЛИЦЯ РЕЗУЛЬТАТІВ -->
                <div class="card-header bg-secondary text-white text-center">
                        <h5 class="mb-0">📈 Результати розрахунків</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0" id="resultsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Показник</th>
                                    <th class="text-end" style="width: 30%;">Значення</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Назва наради</td>
                                    <td id="resMeetingName" class="text-end fw-bold text-dark">-</td>
                                </tr>
                                
                                <tr>
                                    <td>Тривалість наради</td>
                                    <td id="resDuration" class="text-end fw-bold text-dark">0 хв</td>
                                </tr>
                    
                                <!-- НОВИЙ РЯДОК: ДИСЦИПЛІНА -->
                                <tr class="table-warning">
                                    <td class="fw-bold text-warning-dark">Індекс дисципліни команди</td>
                                    <td id="resDiscipline" class="text-end fw-bold text-warning-dark">100%</td>
                                </tr>
                                <tr class="table-danger">
                                    <td class="fw-bold text-danger">Орієнтовна вартість наради</td>
                                    <td id="resCost" class="text-end fw-bold text-danger">0.00 грн</td>
                                </tr>

                                <tr class="table-primary">
                                    <td class="fw-bold text-primary">Потужність потенціалу наради</td>
                                    <td id="resPotential" class="text-end fw-bold text-primary">0.00 балів</td>
                                </tr>
                                <tr class="table-success">
                                    <td class="fw-bold text-success">Ефективність наради</td>
                                    <td id="resEfficiency" class="text-end fw-bold text-success">0.00%</td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-center bg-white">
                        <button type="button" id="copyToWordBtn"  class="btn btn-primary btn-lg px-4">
                            📋 Скопіювати результат для Word
                        </button>
                    </div>
                </div>


            </div>
        </div>
        <div class="row">
             <!-- ПРАКТИЧНІ РЕКОМЕНДАЦІЇ З ОПТИМІЗАЦІЇ НАРАД -->
            <div class="card shadow-sm mt-4 border-0">
                <div class="card-header bg-dark text-white p-3">
                    <h5 class="mb-0 d-flex align-items-center">
                        🚀 Практичний гайд: Як оптимізувати ресурси команди
                    </h5>
                </div>
                <div class="card-body bg-light p-4">
                    <div class="row g-4">

                        <!-- 1. ЯК СКОРОТИТИ ЧАС -->
                        <div class="col-md-6 col-lg-4">
                            <div class="p-3 bg-white rounded border h-100 shadow-sm border-start border-info border-3">
                                <h6 class="text-info fw-bold mb-2">⏱ Як скоротити час наради?</h6>
                                <ul class="ps-3 text-secondary small lh-base mb-0">
                                    <li class="mb-2"><b>Правило 45/20:</b> Ніколи не бронюйте календарі на 60 або 30 хвилин. Ставте ліміт 45 або 20 хвилин. Стислі дедлайни змушують людей говорити по суті.</li>
                                    <li class="mb-2"><b>Жорсткий модератор:</b> Призначте людину, яка перериває розмови не за темою («флуд») та повертає дискусію до плану.</li>
                                    <li><b>Блокування нових питань:</b> Якщо в процесі виникає нова тема, не обговорюйте її. Запишіть у протокол для окремого короткого розбору.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- 2. ЯК ЗМЕНШИТИ ВАРТІСТЬ -->
                        <div class="col-md-6 col-lg-4">
                            <div class="p-3 bg-white rounded border h-100 shadow-sm border-start border-danger border-3">
                                <h6 class="text-danger fw-bold mb-2">💸 Як зменшити вартість?</h6>
                                <ul class="ps-3 text-secondary small lh-base mb-0">
                                    <li class="mb-2"><b>Селекція за грейдами:</b> Залучайте топменеджерів (<span class="badge bg-secondary">К=2.0</span>) лише на перші 10 хвилин для затвердження або на фінальне голосування, а не на весь мітинг.</li>
                                    <li class="mb-2"><b>Переведення в онлайн:</b> Офлайн зустрічі дорожчі на 20–50%. Якщо питання не вимагає фізичної присутності у цеху чи кабінеті, проводьте його в 
                                
            <a href="https://khnpp.ua" target="_blank" class="text-decoration-none text-primary fw-bold border-bottom border-primary border-2 pb-1">🌐jisti.khnpp.ua</a>

                                    </li>
                                    <li><b>Статус «Слухач»:</b> Замість запрошення «про всяк випадок», залиште людину працювати та просто надішліть їй підсумковий Follow-up.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- 3. ЯК ПІДВИЩИТИ ЕФЕКТИВНІСТЬ -->
                        <div class="col-md-6 col-lg-4">
                            <div class="p-3 bg-white rounded border h-100 shadow-sm border-start border-success border-3">
                                <h6 class="text-success fw-bold mb-2">🏆 Як підвищити ефективність (ККД)?</h6>
                                <ul class="ps-3 text-secondary small lh-base mb-0">
                                    <li class="mb-2"><b>«Ні порядку денного — ні наради»:</b> Скасовуйте зустріч, якщо організатор не надіслав чіткий список питань за 3 години до початку.</li>
                                    <li class="mb-2"><b>Домашня робота:</b> Усі звіти, графіки та цифри мають надсилатися за добу. На нараді їх не повинні «читати з екрана» — там мають лише приймати рішення.</li>
                                    <li><b>Фіксація Action Items:</b> Кожне питання має завершуватися записом: <i>Що робимо ➔ Хто відповідальний ➔ Який дедлайн</i>.</li>
                                </ul>
                            </div>
                        </div>

                        <!-- 4. КОЛИ ТА ЧИМ ЗАМІНИТИ НАРАДУ -->
                        <div class="col-12">
                            <div class="p-4 bg-white rounded border shadow-sm border-start border-warning border-4">
                                <h6 class="text-dark fw-bold mb-3 d-flex align-items-center">
                                    ⚠️ Критичні межі ефективності: Коли нараду потрібно ЗАМІНИТИ?
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6 col-lg-4">
                                        <div class="p-3 bg-light rounded text-center h-100 border border-success border-opacity-25">
                                            <span class="badge bg-success mb-2 px-3 py-2 fs-6">80% – 100% (Зразково)</span>
                                            <p class="text-secondary small mb-0 mt-2">Ідеальний формат. Команда працює злагоджено, рішення приймаються швидко. Продовжуйте в тому ж дусі.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="p-3 bg-light rounded text-center h-100 border border-warning border-opacity-25">
                                            <span class="badge bg-warning text-dark mb-2 px-3 py-2 fs-6">50% – 70% (Сигнал тривоги)</span>
                                            <p class="text-secondary small mb-0 mt-2"><b>Час задуматися.</b> Половина часу витрачається на порожні суперечки. Наступного разу спробуйте вирішити це питання у спільному документі або груповому чаті.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-4">
                                        <div class="p-3 bg-danger bg-opacity-10 rounded text-center h-100 border border-danger border-opacity-25">
                                            <span class="badge bg-danger mb-2 px-3 py-2 fs-6">Нижче 50% (Заборона)</span>
                                            <p class="text-dark small mb-0 mt-2 fw-bold">ПРЯМИЙ ЗБИТОК. Такі наради мають бути ЗАБОРОНЕНІ.</p>
                                            <p class="text-secondary small mb-0 mt-1">Організатор краде час команди. Повністю відмовляйтеся від живих зустрічей на користь альтернатив.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- АЛЬТЕРНАТИВНІ ІНСТРУМЕНТИ -->
                                <div class="mt-4 pt-3 border-top">
                                    <h6 class="text-secondary fw-bold small text-uppercase mb-3">🛠 Ефективні альтернативи живим нарадам:</h6>
                                    <div class="row g-2 text-secondary small">
                                        <div class="col-md-4">
                                            <div class="p-2 bg-light rounded">
                                                🚀 <b>Для статус-апдейтів (звітів):</b> Замініть на 5-хвилинне відео або короткий текст-звіт у Viber.
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-2 bg-light rounded">
                                                📑 <b>Для погодження документів:</b> Використовуйте режим Зауваження в файле (АСКВД) з коментарями.
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="p-2 bg-light rounded">
                                                💬 <b>Для простих обговорень:</b> Створіть окремий тематичний чат або гілку (Міранда) в месенджері, де кожен відповість асинхронно у свій робочий час.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
   

    <script>

document.addEventListener('DOMContentLoaded', function () {
    // Реєстрація головних елементів форми
    const form = document.getElementById('meetingForm');
  


    // =========================================================================
    // 1. СЛУХАЧІ ПОДІЙ ТА КОРЕНЕВА ЛОГІКА (Оркестратори)
    // =========================================================================
    
    // Головний обробник оновлення даних у калькуляторі
    function handleCalculatorUpdate() {
        const rawData = getFormData();
        const calculatedMetrics = processMetrics(rawData);
        updateUI(calculatedMetrics);
    }

 

    // =========================================================================
    // 2. ФУНКЦІЇ ЗБОРУ ДАНИХ (Data Fetching)
    // =========================================================================
    
    // Збір усіх вхідних значень з HTML форми
    function getFormData() {
        const formatSelect = document.getElementById('meetingFormat');
        return {
            meetingName: document.getElementById('meetingName').value || 'Нарада без назви',
            formatCoef: parseFloat(formatSelect.value) || 1.0,
            formatText: formatSelect.options[formatSelect.selectedIndex].text.split(' [')[0],
            
            startTimeStr: document.getElementById('startTime').value,
            realStartTimeStr: document.getElementById('realstartTime').value,
            ajendaTime:parseFloat(document.getElementById('ajendaTime').value) || 0,
            endTimeStr: document.getElementById('endTime').value,
            hourlyWage: parseFloat(document.getElementById('hourlyWage').value) || 0,
            
            countTop: parseInt(document.getElementById('countTop').value) || 0,
            countMiddle: parseInt(document.getElementById('countMiddle').value) || 0,
            countRegular: parseInt(document.getElementById('countRegular').value) || 0,
            countLate: parseInt(document.getElementById('countlate').value) || 0,
            countLeave: parseInt(document.getElementById('countleave').value) || 0,
            countPass: parseInt(document.getElementById('countpass').value) || 0,
            questionsBefore: parseInt(document.getElementById('questionsBefore').value) || 0,
            readiness: (parseFloat(document.getElementById('readiness').value) || 0) / 100,
            questionsNew: parseInt(document.getElementById('questionsNew').value) || 0,
            
            decisionsAdopted: parseInt(document.getElementById('decisionsAdopted').value) || 0,
            decisionsReviewed: parseInt(document.getElementById('decisionsReviewed').value) || 0,
            decisionsDelayed: parseInt(document.getElementById('decisionsDelayed').value) || 0
        };
    }

    // Збір поточних текстових значень з таблиці результатів екранної форми
    function getUIDataForReport() {
        const resDisciplineElem = document.getElementById('resDiscipline');
        const topCount = parseFloat(document.getElementById('countTop').value) || 0;
        const middleCount = parseFloat(document.getElementById('countMiddle').value) || 0;
        const regularCount = parseFloat(document.getElementById('countRegular').value) || 0;

        return {
            mName: document.getElementById('resMeetingName').innerText,
            mFormat: document.getElementById('meetingFormat').value,
            tPart: topCount + middleCount + regularCount,
            wPart: (topCount * 2) + (middleCount * 1.5) + regularCount,
            dur: document.getElementById('resDuration').innerText,
            disc: resDisciplineElem ? resDisciplineElem.innerText : '100%',
            cost: document.getElementById('resCost').innerText,
            pot: document.getElementById('resPotential').innerText,
            eff: document.getElementById('resEfficiency').innerText
        };
    }

    // =========================================================================
    // 3. ФУНКЦІЇ МАТЕМАТИЧНИХ РОЗРАХУНКІВ (Business Logic)
    // =========================================================================
    
    // Обчислення всіх підсумкових метрик на основі вхідного об'єкта даних
  function processMetrics(data) {
  // Конвертація часу типу "09:30" у хвилини від початку доби
  const timeToMinutes = (str) => {
    if (!str) return 0;
    const [h, m] = str.split(':').map(Number);
    return (h * 60) + m;
  };

  // Розрахунок тривалості від РЕАЛЬНОГО старту (якщо є) до кінця наради
  const activeStartTime = data.realStartTimeStr || data.startTimeStr;
  let durationMinutes = timeToMinutes(data.endTimeStr) - timeToMinutes(activeStartTime);
  if (durationMinutes < 0) durationMinutes += 24 * 60;
  const durationHours = durationMinutes / 60;

  // Кількість учасників (захист від undefined)
  const countTop = data.countTop || 0;
  const countMiddle = data.countMiddle || 0;
  const countRegular = data.countRegular || 0;
  
  const totalParticipants = countTop + countMiddle + countRegular;
  const baseWeighted = (countTop * 2.0) + (countMiddle * 1.5) + (countRegular * 1.0);
  const formatCoef = data.formatCoef || 1.0;
  const weightedParticipants = baseWeighted * formatCoef;

  // Розрахунок дисципліни команди
  const discipline = calculateDisciplineScore(data, totalParticipants, timeToMinutes);

  // Фінансові показники та потенціал
  const hourlyWage = data.hourlyWage || 0;
  const totalCost = weightedParticipants * durationHours * hourlyWage;
  
  const questionsBefore = data.questionsBefore || 0;
  const questionsNew = data.questionsNew || 0;
  const readiness = data.readiness || 0;
  
  const preparedQuestionsFactor = questionsBefore * readiness;
  const potentialPower = (weightedParticipants * (preparedQuestionsFactor + 1)) / (questionsNew + 1);

  // Розрахунок базової ефективності
  const totalQuestions = questionsBefore + questionsNew;
  const baseEfficiency = calculateEfficiencyScore(data, totalQuestions, discipline);

  // Розрахунок коефіцієнта навантаження (питання / персонал)
  const questionsPerParticipant = totalParticipants > 0 ? totalQuestions / totalParticipants : 0;

  // Розрахунок модифікатора навантаження з виправленими межами
  let loadModifier = 1.0;
  if (totalParticipants === 0 || questionsPerParticipant === 0) {
    loadModifier = 0.7; // Штраф за безцільну нараду або відсутність людей
  } else if (questionsPerParticipant < 1) {
    loadModifier = 0.9; // Легкий штраф, якщо питань замало для такої кількості людей
  } else if (questionsPerParticipant > 3) {
    loadModifier = Math.max(0.5, 1 - (questionsPerParticipant - 3) * 0.1); // Плавне зниження при перевантаженні
  } else {
    loadModifier = 1.1; // Бонус за оптимальну кількість питань (від 1 до 3 на людину)
  }

  // Фінальна ефективність із жорстким обмеженням (максимум 100%)
  const efficiency = Math.min(100, baseEfficiency * loadModifier);

  // Формування назви формату зустрічі
  const formatText = data.formatText || (formatCoef === 1.0 ? 'Стандарт' : 'Власний формат');

  return {
    meetingName: data.meetingName || 'Нарада без назви',
    formatTextWithCoef: `${formatText} (К=${formatCoef})`,
    totalParticipants,
    weightedParticipants,
    durationMinutes,
    durationHours,
    discipline,
    totalCost,
    potentialPower,
    efficiency
  };
}



    // Допоміжна ізольована функція обчислення дисципліни
  
    function calculateDisciplineScore(data, totalParticipants, timeToMinutesFn) {
    let score = 100;

    // =========================================================================
    // 1. ШТРАФ ЗА ДИСЦИПЛІНУ ОКРЕМІХ УЧАСНИКІВ
    // =========================================================================
    if (totalParticipants > 0) {
        const latePenalty = (data.countLate / totalParticipants) * 30;
        const leavePenalty = (data.countLeave / totalParticipants) * 30;
        const passPenalty = (data.countPass / totalParticipants) * 40;
        score -= (latePenalty + leavePenalty + passPenalty);
    }

    // =========================================================================
    // 2. ДВОСТОРУННІЙ КОНТРОЛЬ ЧАСУ СТАРТУ (Запізнення / Передчасний запуск)
    // =========================================================================
    if (data.startTimeStr && data.realStartTimeStr) {
        const planStartMin = timeToMinutesFn(data.startTimeStr);
        const realStartMin = timeToMinutesFn(data.realStartTimeStr);
        
        if (realStartMin > planStartMin) {
            // --- СЦЕНАРІЙ А: ЗАПІЗНЕННЯ СТАРТУ (Пізно) ---
            const delayStartMin = realStartMin - planStartMin;
            // Штраф: -2% за кожну хвилину очікування (максимум -30%)
            // Усі люди сиділи і марно чекали початку
            score -= Math.min(30, delayStartMin * 2); 
            
        } else if (realStartMin < planStartMin) {
            // --- СЦЕНАРІЙ Б: ПЕРЕДЧАСНИЙ ЗАПУСК (Рано) ---
            const earlyStartMin = planStartMin - realStartMin;
            
            // Похибка в 1-2 хвилини на підключення — це нормально. Але більше 2 хвилин — штраф.
            if (earlyStartMin > 2) {
                // Штраф: -3% за кожну хвилину передчасного старту (максимум -30%)
                // Чому штраф вищий? Бо це грубо смикає людей з інших процесів та ламає їхні календарі.
                score -= Math.min(30, earlyStartMin * 3);
            }
        }
    }

    // =========================================================================
    // 3. ДВОСТОРУННІЙ КОНТРОЛЬ ТРИВАЛОСТІ (Overtime / Undertime)
    // =========================================================================
    if (data.realStartTimeStr && data.endTimeStr && data.ajendaTime > 0) {
        let actualDurationMin = timeToMinutesFn(data.endTimeStr) - timeToMinutesFn(data.realStartTimeStr);
        if (actualDurationMin < 0) actualDurationMin += 24 * 60; // Перехід через північ

        if (actualDurationMin > data.ajendaTime) {
            // --- СЦЕНАРІЙ А: ПЕРЕВИЩЕННЯ ЧАСУ (Затягнули) ---
            const overtimeMin = actualDurationMin - data.ajendaTime;
            score -= Math.min(30, overtimeMin * 1.5);
            
        } else if (actualDurationMin < data.ajendaTime) {
            // --- СЦЕНАРІЙ Б: НАРАДА ЗАВЕРШИЛАСЬ РАНІШЕ (Недоліміт) ---
            const savedMin = data.ajendaTime - actualDurationMin;
            
            if (savedMin > 5) {
                const undertimeRatio = savedMin / data.ajendaTime;
                const badPlanningPenalty = undertimeRatio * 40; 
                score -= Math.min(40, badPlanningPenalty);
            }
        }
    }

    // Обмежуємо фінальний результат у суворих межах від 0% до 100%
    return Math.max(0, Math.round(score));
}

    // Допоміжна ізольована функція обчислення ККД ефективності
    function calculateEfficiencyScore(data, totalQuestions, disciplineScore) {
        const positiveOutcome = data.decisionsAdopted;
        const negativeOutcome = (data.decisionsReviewed * 0.5) + (data.decisionsDelayed * 0.7);
        
        if (totalQuestions > 0 && positiveOutcome > 0) {
            const successRate = (positiveOutcome - negativeOutcome) / totalQuestions;
            const disciplineModifier = disciplineScore / 100;
            return Math.max(0, successRate * data.readiness * disciplineModifier * 100);
        }
        return 0;
    }

    // =========================================================================
    // 4. ФУНКЦІЇ ОНОВЛЕННЯ ІНТЕРФЕЙСУ ТА РЕНДЕРИНГУ (DOM & UI)
    // =========================================================================
    
    // Вивід прорахованих об'єктів метрик у відповідні ID екранної таблиці
    function updateUI(metrics) {
       document.getElementById('resMeetingName').innerText = metrics.meetingName;
       document.getElementById('resDuration').innerText = `${metrics.durationMinutes} хв (${metrics.durationHours.toFixed(2)} год)`;
        
        const resDisciplineElem = document.getElementById('resDiscipline');
        if (resDisciplineElem) {
            resDisciplineElem.innerText = `${metrics.discipline}%`;
        }

        document.getElementById('resCost').innerText = `${metrics.totalCost.toFixed(2)} грн`;
        document.getElementById('resPotential').innerText = `${Math.max(0, metrics.potentialPower).toFixed(2)} балів`;
        document.getElementById('resEfficiency').innerText = `${metrics.efficiency.toFixed(2)}%`;
    }

    // Ініціалізація слухачів подій
    if (form) {
        form.addEventListener('input', handleCalculatorUpdate);
    }

function generateWordTableHTML(ui) {
    const currentDate = new Date().toLocaleString('uk-UA', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    // Спеціальна обгортка для MS Word, яка гарантує збереження шрифтів та стилів таблиці
    return `
        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:w="urn:schemas-microsoft-com:office:word" xmlns="http://w3.org">
        <head>
            <meta charset="utf-8">
            <style>
                body { font-family: 'Segoe UI', Arial, sans-serif; color: #333333; line-height: 1.4; }
                h3 { color: #1a1a1a; margin-bottom: 5px; font-size: 16pt; border-bottom: 2px solid #0056b3; padding-bottom: 5px; }
                .meta-date { font-size: 10pt; color: #666666; margin-bottom: 20px; font-style: italic; }
                table { border-collapse: collapse; width: 100%; font-size: 11pt; margin-top: 15px; }
                th { background-color: #212529; color: #ffffff; font-weight: bold; text-align: left; padding: 10px; border: 1px solid #343a40; }
                td { padding: 10px; border: 1px solid #dee2e6; vertical-align: middle; }
                .row-even { background-color: #f8f9fa; }
                .text-right { text-align: right; }
                .font-bold { font-weight: bold; }
                /* Кольорові бейджі для бізнес-метрики */
                .badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; display: inline-block; }
                .badge-discipline { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
                .badge-cost { background-color: #fce8e6; color: #c5221f; border: 1px solid #fad2cf; }
                .badge-potential { background-color: #e8f0fe; color: #1a73e8; border: 1px solid #d2e3fc; }
                .badge-efficiency { background-color: #e6f4ea; color: #137333; border: 1px solid #ceead6; }
            </style>
        </head>
        <body>
            <h3>ЗВІТ ПРО ЕФЕКТИВНІСТЬ ТА ПОТЕНЦІАЛ НАРАДИ</h3>
            <div class="meta-date">Дата генерації звіту: ${currentDate}</div>
            
            <table>
                <thead>
                    <tr>
                        <th>Показник наради</th>
                        <th class="text-right" style="width: 35%;">Значення</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><b>Назва наради</b></td>
                        <td class="text-right font-bold" style="word-break: break-word; max-width: 300px;">${ui.mName}</td>
                    </tr>
                    <tr class="row-even">
                        <td>Формат та коефіцієнт місця</td>
                        <td class="text-right">${ui.mFormat}</td>
                    </tr>
                    <tr>
                        <td>Загальна кількість учасників</td>
                        <td class="text-right">${ui.tPart} осіб</td>
                    </tr>
                    <tr class="row-even">
                        <td>Зважена кількість учасників (з урахуванням К)</td>
                        <td class="text-right font-bold">${ui.wPart}</td>
                    </tr>
                    <tr>
                        <td>Фактична тривалість зустрічі</td>
                        <td class="text-right">${ui.dur}</td>
                    </tr>
                    <tr class="row-even">
                        <td><b>Індекс дисципліни команди</b></td>
                        <td class="text-right">
                            <span class="badge badge-discipline">${ui.disc}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Орієнтовна вартість наради для компанії</b></td>
                        <td class="text-right">
                            <span class="badge badge-cost">${ui.cost}</span>
                        </td>
                    </tr>
                    <tr class="row-even">
                        <td><b>Потужність потенціалу наради</b></td>
                        <td class="text-right">
                            <span class="badge badge-potential">${ui.pot}</span>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Фінальна ефективність (ККД)</b></td>
                        <td class="text-right">
                            <span class="badge badge-efficiency">${ui.eff}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </body>
        </html>
    `;
}

const copyBtn = document.getElementById('copyToWordBtn');
copyBtn.addEventListener('click', async function() {
    // 1. Отримуємо актуальні дані з екрана та генеруємо HTML для Word
    const uiData = getUIDataForReport();
    const htmlContent = generateWordTableHTML(uiData);

    const originalHTML = copyBtn.innerHTML;

    // Спроба №1: Сучасний і красивий метод (Працює на HTTPS, Localhost та нових браузерах)
    if (navigator.clipboard && window.ClipboardItem) {
        try {
            const blob = new Blob([htmlContent], { type: 'text/html' });
            const data = [new ClipboardItem({ 'text/html': blob })];
            await navigator.clipboard.write(data);
            showSuccessAnimation();
            return; // Виходимо, якщо все пройшло успішно
        } catch (modernErr) {
            console.warn('Сучасний Clipboard API заблоковано, перемикаємось на резервний метод...', modernErr);
        }
    }

    // Спроба №2: Резервний "класичний" метод (Працює на file://, HTTP та старих пристроях)
    try {
        // Створюємо тимчасовий прихований контейнер
        const container = document.createElement('div');
        container.style.position = 'fixed';
        container.style.pointerEvents = 'none';
        container.style.opacity = '0';
        container.style.left = '-9999px'; // Виносимо за межі екрана
        container.innerHTML = htmlContent;
        document.body.appendChild(container);

        // Виділяємо весь текст і таблицю всередині контейнера
        window.getSelection().removeAllRanges();
        const range = document.createRange();
        range.selectNode(container);
        window.getSelection().addRange(range);

        // Виконуємо копіювання виділеного фрагмента
        const success = document.execCommand('copy');

        // Очищаємо за собою DOM та виділення
        window.getSelection().removeAllRanges();
        document.body.removeChild(container);

        if (success) {
            showSuccessAnimation();
        } else {
            throw new Error('execCommand повернув false');
        }
    } catch (fallbackErr) {
        // Якщо заблоковано взагалі все (наприклад, жорсткі корпоративні політики безпеки)
        console.error('Обидва методи копіювання дали збій: ', fallbackErr);
        alert('Не вдалося автоматично скопіювати через обмеження безпеки вашого браузера. Будь ласка, просто виділіть таблицю результатів на екрані мишкою та натисніть Ctrl+C.');
    }

    // Локальна функція для візуальної анімації кнопки
    function showSuccessAnimation() {
        copyBtn.innerHTML = '✅ Таблицю скопійовано!';
        copyBtn.classList.remove('btn-primary');
        copyBtn.classList.add('btn-success');
        
        setTimeout(() => {
            copyBtn.innerHTML = originalHTML;
            copyBtn.classList.remove('btn-success');
            copyBtn.classList.add('btn-primary');
        }, 2000);
    }
});


// Навішування подій для миттєвого перерахунку при введенні
if (form) {
    form.addEventListener('input', handleCalculatorUpdate);
    form.addEventListener('change', handleCalculatorUpdate);
}

// Запуск початкового розрахунку
handleCalculatorUpdate();

    });
    
</script>


@endsection
