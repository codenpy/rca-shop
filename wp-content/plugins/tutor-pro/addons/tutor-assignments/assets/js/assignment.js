window.jQuery(document).ready(function(a){var t=wp.i18n,e=t.__;t._x,t._n,t._nx;function n(t,n){var o=new URL(window.location.href),s=o.searchParams;return s.set(t,n),o.search=s.toString(),s.set("paged",1),o.search=s.toString(),o.toString()}a('[data-assignment_action="delete"]').click(function(t){t.preventDefault();var n=a(this),o=n.data("warning_message"),t=n.data("assignment_id"),s=n.closest("tr");window.confirm(o)&&(n.addClass("tutor-updating-message"),a.ajax({url:window.ajaxurl,data:{action:"delete_tutor_course_assignment_submission",assignment_id:t},success:function(t){t.success?(tutor_toast(e("Success","tutor-pro"),n.data("toast_success_message"),"success"),s.fadeOut("fast",function(){a(this).remove()})):tutor_toast(e("Error","tutor-pro"),e("Action Failed","tutor-pro"),"error")},complete:function(){n&&n.length&&n.removeClass("tutor-updating-message")}}))}),a(".tutor-assignment-course-sorting").on("change",function(t){window.location=n("course-id",a(this).val())}),a(".tutor-assignment-order-sorting").on("change",function(t){window.location=n("order",a(this).val())}),a(".tutor-assignment-date-sorting").on("change",function(t){window.location=n("date",a(this).val())}),a(".tutor-assignment-search-sorting").on("click",function(t){window.location=n("search",a(".tutor-assignment-search-field").val())})});