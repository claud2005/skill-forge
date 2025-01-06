$(document).ready(function () {
    $('#salvarPergunta').click(function () {
        var tituloPergunta = $('#tituloPergunta').val();
        var respostaA = $('#respostaA_texto').val();
        var respostaB = $('#respostaB_texto').val();
        var respostaC = $('#respostaC_texto').val();
        var respostaD = $('#respostaD_texto').val();
        var respostaCorreta = $('input[name="resposta"]:checked').val();
        var idCurso = $('#idCurso').val();

        if (tituloPergunta && respostaA && respostaB && respostaC && respostaD && respostaCorreta) {
            $.ajax({
                url: '/src/controlador/admin/salvar-pergunta.php',
                type: 'POST',
                data: {
                    tituloPergunta: tituloPergunta,
                    respostaA: respostaA,
                    respostaB: respostaB,
                    respostaC: respostaC,
                    respostaD: respostaD,
                    respostaCorreta: respostaCorreta,
                    idCurso: idCurso
                },
                success: function (response) {
                    try {
                        var resposta = JSON.parse(response);
                        if (resposta.sucesso) {
                            alert(resposta.mensagem);
                            $('#modalPerguntas').modal('hide');
                        } else {
                            alert(resposta.mensagem);
                        }
                    } catch (e) {
                        alert('Erro no processamento da resposta do servidor.');
                    }
                },
                error: function () {
                    alert('Erro na comunicação com o servidor.');
                }
            });
        } else {
            alert('Por favor, preencha todos os campos!');
        }
    });
});
