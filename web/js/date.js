






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










		$("#cronograma_fechaIniciacion").datepicker({
			dateFormat: "dd-mm-yy",
			onSelect: function(dateText, inst) {
				
			}
			}); //date picker


		$("#btnsubir").click(function(){
			var codigo = "";
			var actividad = "";
			var fecha = "";

				//codigo = this.value;
				actividad = $("#input_actividad").val();
				fecha = $("#cronograma_fechaIniciacion").val();
				var ruta = $("#aggacti").attr("data-path");



				$.ajax({url: ruta,
					type: "POST",
					data: { "fecha" : fecha , "nombre" : actividad },
					success:function(data){
						alert(data);
							//console.log(data);
							$("#input_actividad").val("");
							$("#cronograma_fechaIniciacion").val("");
							var html = '';

							html = '<tr><td class = ';
							html = html + '"colActividad">';
							html = html + actividad;
							html = html + '</td><td class = ';
							html = html + '"colFecha">';
							html = html + fecha;
							html = html + '</td>';
							html = html + '<td><a  class="eliminaa"><img src="/complementaria/web/img/delete.png"/></a></td></tr>';
							
							$(".tabla").append(html);										

							$("#error").remove();
							$(".eliminaa").click(function(){

								fn_dar_eliminar(this);

							});
						}});
			});




function fn_dar_eliminar(x){
		//var fechaEliminar = $(this).val()
		var id;
		var respuesta;
		//id = $(x).parent("tr").find("td").eq(0).html();
		var parent = $(x).parents("tr");
		id = parent.find("td").eq(0).html();
		var fechaEliminada = parent.find("td").eq(1).html();
		respuesta = confirm("Desea eliminar el usuario: " + id);
		if (respuesta){

			$(x).parents("tr").fadeOut("normal", function(){
				$(x).remove();
				//alert("Usuario " + id + " eliminado")
                            /*
                                aqui puedes enviar un conjunto de datos por ajax
                                $.post("eliminar.php", {ide_usu: id})
                                */
                            })
		}

	};

		}); // document.ready
}); 


