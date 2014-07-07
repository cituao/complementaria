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
					var fecha_inicio = $.datepicker.parseDate(inst.settings.dateFormat || $.datepicker._defaults.dateFormat, dateText, inst.settings);
					var fecha_pivote = fecha_inicio;
					
					
					//fecha para la primera asesoría tiempo estimado 7 días despues de iniciar el cronograma
					fecha_pivote.setDate(fecha_pivote.getDate() + 7);
					var fecha_asesoria1 = $.datepicker.formatDate("dd-mm-yy", fecha_pivote, inst.settings);
					//calcular la asesoría #2 tiempo estimado 15
					fecha_pivote.setDate(fecha_pivote.getDate() + 15); //un mes 
					var fecha_asesoria2 = $.datepicker.formatDate("dd-mm-yy", fecha_pivote, inst.settings);
					//calcular la asesoría #3 tiempo estimado 20 días    
					fecha_pivote.setDate(fecha_pivote.getDate() + 20); //dos meses
					var fecha_asesoria3 = $.datepicker.formatDate("dd-mm-yy", fecha_pivote, inst.settings);
					//calcular la asesoría #4 tiempo estimado 25 días    
					fecha_pivote.setDate(fecha_pivote.getDate() + 25); //tres meses
					var fecha_asesoria4 = $.datepicker.formatDate("dd-mm-yy", fecha_pivote, inst.settings);
					//calcular la asesoría #5 tiempo estimado 25 días    
					fecha_pivote.setDate(fecha_pivote.getDate() + 25); //cuatro meses
					var fecha_asesoria5 = $.datepicker.formatDate("dd-mm-yy", fecha_pivote, inst.settings);
					//calcular la asesoría #6 tiempo estimado 25 días	 
					fecha_pivote.setDate(fecha_pivote.getDate() + 25); //cinco meses
					var fecha_asesoria6 = $.datepicker.formatDate("dd-mm-yy", fecha_pivote, inst.settings);
					//calcular la asesoría #7 tiempo estimado 25 días
					fecha_pivote.setDate(fecha_pivote.getDate() + 25); //seis meses
					var fecha_asesoria7 = $.datepicker.formatDate("dd-mm-yy", fecha_pivote, inst.settings);

					//calcular la fecha de finalizacion 30 dias antes del proceso
					//a 8 días de diferencia
					fecha_pivote.setDate(fecha_pivote.getDate() + 8); //fecha fin de proceso
					var fecha_informe_final = $.datepicker.formatDate("dd-mm-yy", fecha_pivote, inst.settings);
						
					
					//colocar las fechas de asesorias para los practicantes
					$("#cronograma_fechaAsesoria1").val(fecha_asesoria1);
					$("#cronograma_fechaAsesoria2").val(fecha_asesoria2);
					$("#cronograma_fechaAsesoria3").val(fecha_asesoria3);
					$("#cronograma_fechaAsesoria4").val(fecha_asesoria4);
					$("#cronograma_fechaAsesoria5").val(fecha_asesoria5);
					$("#cronograma_fechaAsesoria6").val(fecha_asesoria6);
					$("#cronograma_fechaAsesoria7").val(fecha_asesoria7);
					$("#cronograma_fechaInformeFinal").val(fecha_informe_final);
					if ($("#crono_pra_proyecto").length){
						$("#crono_pra_proyecto").val(fecha_informe_final);
					}

					//colocar las fechas para el asesor academico
					$("#crono_aca_asesoria1").val(fecha_asesoria1);
					$("#crono_aca_asesoria2").val(fecha_asesoria2);
					$("#crono_aca_asesoria3").val(fecha_asesoria3);
					$("#crono_aca_asesoria4").val(fecha_asesoria4);
					$("#crono_aca_asesoria5").val(fecha_asesoria5);
					$("#crono_aca_asesoria6").val(fecha_asesoria6);
					$("#crono_aca_asesoria7").val(fecha_asesoria7);
					$("#crono_aca_evaluacionfinal").val(fecha_informe_final);

					//calcular  fechas para la visita y evaluacion #1
					//devolvemos la fecha a su inicio han pasado 150 
					fecha_pivote.setDate(fecha_pivote.getDate() - 150);
					// la fecha de evaluacion 1 es 60 días despues de iniciar proceso
					fecha_pivote.setDate(fecha_pivote.getDate() + 60);
					var fecha_evaluacion1 = $.datepicker.formatDate("dd-mm-yy", fecha_pivote, inst.settings);
					// la fecha de evaluacion 2 es 120 días despues de iniciar proceso
					fecha_pivote.setDate(fecha_pivote.getDate() + 60);
					var fecha_evaluacion2 = $.datepicker.formatDate("dd-mm-yy", fecha_pivote, inst.settings);
					

					//colocar las fechas de visitas practicantes
					$("#cronograma_fechaVisitaP").val(fecha_asesoria1);
					$("#cronograma_fechaVisita1").val(fecha_evaluacion1);
					$("#cronograma_fechaVisita2").val(fecha_evaluacion2);

					//colocar las fechas para el asesor academico
					$("#crono_aca_evaluacion1").val(fecha_evaluacion1);
					$("#crono_aca_evaluacion2").val(fecha_evaluacion2);	
					
					//fechas para el asesor externo
					$("#crono_ext_evaluacion1").val(fecha_evaluacion1);
					$("#crono_ext_evaluacion2").val(fecha_evaluacion2);	
					
					
					//calcular  fechas para informes de gestión cualicuanti
					//devolvemos la fecha a su inicio han pasado 120 
					fecha_pivote.setDate(fecha_pivote.getDate() - 120);
					// la fecha de gestión 1 es 45 días despues de iniciar proceso
					fecha_pivote.setDate(fecha_pivote.getDate() + 45);
					var fecha_gestion1 = $.datepicker.formatDate("dd-mm-yy", fecha_pivote, inst.settings);
					// la fecha de gestión 2 es 120 días despues de iniciar proceso
					fecha_pivote.setDate(fecha_pivote.getDate() + 45);
					var fecha_gestion2 = $.datepicker.formatDate("dd-mm-yy", fecha_pivote, inst.settings);
					// la fecha de gestión 2 es 120 días despues de iniciar proceso
					fecha_pivote.setDate(fecha_pivote.getDate() + 45);
					var fecha_gestion3= $.datepicker.formatDate("dd-mm-yy", fecha_pivote, inst.settings);
					
					
					$("#cronograma_fechaInformeGestion1").val(fecha_gestion1);
					$("#cronograma_fechaInformeGestion2").val(fecha_gestion2);
					$("#cronograma_fechaInformeGestion3").val(fecha_gestion3);
					
					$("#crono_aca_gestion1").val(fecha_gestion1);
					$("#crono_aca_gestion2").val(fecha_gestion2);
					$("#crono_aca_gestion3").val(fecha_gestion3);

					
					//calcular la fecha de acta de finalización para el asesor externo
					//devolvemos la fecha 135 días 

					fecha_pivote.setDate(fecha_pivote.getDate() - 135);
					// la fecha de gestión 1 es 45 días despues de iniciar proceso
					fecha_pivote.setDate(fecha_pivote.getDate() + 176);
					var fecha_acta = $.datepicker.formatDate("dd-mm-yy", fecha_pivote, inst.settings);
					
					$("#crono_ext_acta").val(fecha_acta);	


				}
			}); //date picker


		$("#cronograma_centro").change(function(){
				var codigo = "";
	
				codigo = this.value;
				//alert(codigo);
				
				var ruta = $("#ruta").attr("data-path");
				//alert(ruta);
					$.ajax({url: ruta,
						type: "POST",
						data: { "cod_centro" : codigo },
						success:function(data){
							//alert(data);
							//console.log(data);

							var obj = eval ("(" + data + ")");
							//console.log(obj);

							var html = '';
							var html = '<option value selected="selected">Seleccione un asesor externo</option>'
							var len = obj.length;
							for (var i = 0; i< len; i++) {
								html += '<option value="' + obj[i].id + '">' + obj[i].nombres + ' ' + obj[i].apellidos + '</option>';
							}
							//alert(html);
							$('#cronograma_externo')
								.find('option')
								.remove()
								.end()
								.append(html);
							
					}});
		});
		
		

		}); // document.ready
}); 

