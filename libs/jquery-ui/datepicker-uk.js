/* Ukrainian (UTF-8) initialisation for the jQuery UI date picker plugin. */
/* Written by Vladimir Litvinchik (litvinchik.vladimir@gmail.com). */
jQuery(function ($) {
    $.datepicker.regional['ua'] = {
        closeText: 'Закрити',
        prevText: 'Попередній',
        nextText: 'Настyпний',
        currentText: 'Сьогодні',
        monthNames: ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'],
        monthNamesShort: ['Січ','Лют', 'Бер', 'Квіт', 'Трав', 'Черв', 'Лип', 'Серп', 'Вер', 'Жовт', 'Лист', 'Груд'],
        dayNames: ['неділя', 'понеділок', 'вівторок', 'среда', 'четвер', 'п\'ятница', 'субота'],
        dayNamesShort: ['нд', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб'],
        dayNamesMin: ['Нд', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
        weekHeader: 'Ти',
        dateFormat: 'dd-mm-yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
	$.timepicker.regional['ua'] = {
        timeFormat: 'hh:mm',
		currentText: 'Поточний',
		ampm: false,
		timeOnlyTitle: 'Виберіть час',
		timeText: 'Час',
		hourText: 'Години',
		minuteText: 'Хвилини',
		secondText: 'Секунди',
    };
    $.datepicker.setDefaults($.datepicker.regional['ua']);
    $.timepicker.setDefaults($.timepicker.regional['ua']);
});
