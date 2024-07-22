// Mascara de CPF e CNPJ
var CpfCnpjMaskBehavior = function (val) {
      return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
    },
    cpfCnpjpOptions = {
      onKeyPress: function(val, e, field, options) {
        field.mask(CpfCnpjMaskBehavior.apply({}, arguments), options);
      }
    };


$(function(){
    $('#cep').mask('99999-999');
    $('#telefone').mask('(99) 9999-9999');
    $('#zap').mask('(99) 99999-9999');
    $('#inscricao').mask('00.000.00-0');
    $('.valor').mask('000.000.000.000.000,00', {reverse: true});
  
    $('#cnpj').mask(CpfCnpjMaskBehavior, cpfCnpjpOptions);
  
  });
