$(document).ready(function() {

now = new Date;
today = now.getFullYear() + '-' + (now.getMonth() +1) + '-' + now.getDate();

    $('#calendar').fullCalendar({

      header: {

        left: 'prev,next today',

        center: 'title',

        right: 'month,agendaWeek,agendaDay'

      },


      //defaultDate: today,

      navLinks: true, // can click day/week names to navigate views

      selectable: true,

      selectHelper: true,

      select: function(start, end) {

        //var title = prompt('Event Title:');

        var eventData;

        if (title) {

          eventData = {

            title: title,

            start: start,

            end: end

          };

          $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true

        }

        $('#calendar').fullCalendar('unselect');

      },

      locale: "pt-br",

      editable: true,

      eventLimit: true, // allow "more" link when too many events

      events: [

        {

          title: 'All Day Event',

          start: '2022-04-09'

        },

        {

          title: 'Long Event',

          start: '2022-04-11',

          end: '2022-04-13'

        },

        {

          title: 'Meeting',

          start: '2022-04-12T10:30:00',

          end: '2022-04-12T12:30:00'

        },

        {

          title: 'Lunch',

          start: '2022-04-12T12:00:00'

        },

        {

          title: 'Meeting',

          start: '2022-04-12T14:30:00'

        },

        {

          title: 'Happy Hour',

          start: '2022-04-12T17:30:00'

        },

        {

          title: 'Dinner',

          start: '2022-04-12T20:00:00'

        },

        {

          title: 'Birthday Party',

          start: '2022-04-13T07:00:00'

        },

        {

          title: 'Click for Google',

          url: 'http://google.com/',

          start: '2022-04-28'

        }

      ]

    });



  });