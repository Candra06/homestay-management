
document.addEventListener('DOMContentLoaded', function () {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
    },
    initialDate: '2026-08-01',
    navLinks: true, // can click day/week names to navigate views
    businessHours: true, // display business hours
    selectable: true,
    selectMirror: true,
    droppable: true, // this allows things to be dropped onto the calendar

    select: function (arg) {
      var title = prompt('Event Title:');
      if (title) {
        calendar.addEvent({
          title: title,
          start: arg.start,
          end: arg.end,
          allDay: arg.allDay
        })
      }
      calendar.unselect()
    },
    eventClick: function (arg) {
      console.log('eventClick', arg.event);
    },
    editable: true,
    dayMaxEvents: true, // allow "more" link when too many events
    events: [{
        title: 'Business Lunch',
        start: '2026-08-03T13:00:00',
        constraint: 'businessHours'
      },
      {
        title: 'Meeting',
        start: '2026-08-13T11:00:00',
        constraint: 'availableForMeeting', // defined below
        color: '#257e4a'
      },
      {
        title: 'Deluxe(101)',
        start: '2026-08-18',
        end: '2026-08-21'
      },
      {
        title: 'Deluxe(102)',
        start: '2026-08-18',
        end: '2026-08-19'
      },
       {
        title: 'Standart(202)',
        start: '2026-08-18',
        end: '2026-08-19'
      },
      {
        title: 'Party',
        start: '2026-08-29T20:00:00'
      },

      // areas where "Meeting" must be dropped
      {
        groupId: 'availableForMeeting',
        start: '2026-08-11T10:00:00',
        end: '2026-08-11T16:00:00',
        display: 'background'
      },
      {
        groupId: 'availableForMeeting',
        start: '2026-08-13T10:00:00',
        end: '2026-08-13T16:00:00',
        display: 'background'
      },

      // red areas where no events can be dropped
      {
        start: '2026-08-24',
        end: '2026-08-28',
        overlap: false,
        display: 'background',
        color: 'transparent'
      },
      {
        start: '2026-08-06',
        end: '2026-08-08',
        overlap: false,
        display: 'background',
        color: 'transparent'
      }
    ]
  });
  calendar.render();

});
