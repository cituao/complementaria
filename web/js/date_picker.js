$.datepicker.regional['es'] = {
	closeText: 'Cerrar',
	prevText: '<Ant',
	nextText: 'Sig>',
	currentText: 'Hoy',
	monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
	monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
	dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
	dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
	dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
	weekHeader: 'Sm',
	dateFormat: 'dd/mm/yy',
	firstDay: 1,
	isRTL: false,
	showMonthAfterYear: false,
	yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['es']);

$(function() {

	$(document).ready(function(){
		function validate(a){
			//if it's NOT valid
			if(a.length < 4){
				return false;
			}
			//if it's valid
			else{
				return true;
			}
		}

		$("#estudiante_fechaInicio").datepicker({
			dateFormat: "dd-mm-yy",
			onSelect: function(dateText, inst) {
				
			}
			}); //date picker

		$("#estudiante_fechaFin").datepicker({
			dateFormat: "dd-mm-yy",
			onSelect: function(dateText, inst) {
				
			}
			}); //date picker
	
		$("#profesor_fecha").datepicker({
			dateFormat: "dd-mm-yy",
			onSelect: function(dateText, inst) {
				
			}
			}); //date picker
		}); // document.ready
}); 
