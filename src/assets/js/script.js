$(function(){
    $('input#price').mask('000,000,000.00', {reverse: true});

    var fileInput = $('.upload-file');
    var maxSize = fileInput.data('max-size');
    
    // Se a janela de escolha de arquivo fechar ao dar enter,
    // atualiza tamanho dos arquivos
    $('.upload-file').keydown(function(e){
        if (e.keyPress == 13){
            setTimeout(function(){
                if(fileInput.get(0).files.length){
                    var files = fileInput.get(0).files;
                    var fileSize = 0;
                    
                    for(var i=0; i < files.length; i++){
                        fileSize += files[i].size;
                    }

                    //console.log(fileInput.get(0).files);

                    $('.modal-body p').html('Size: '+(fileSize/Math.pow(10,6)).toFixed(2)+' MB');
                }    
            },150);
        }
    });

    // Se a janela de escolha de arquivo fechar, atualiza tamanho dos arquivos
    $('.upload-file').focus(function(){
        setTimeout(function(){
            if(fileInput.get(0).files.length){
                var files = fileInput.get(0).files;
                var fileSize = 0;
                
                for(var i=0; i < files.length; i++){
                    fileSize += files[i].size;
                }

                //console.log(fileInput.get(0).files);

                $('.upload-file-size').html((fileSize/Math.pow(10,6)).toFixed(2)+' MB');
                if(fileSize > 2000000){
                    $('.upload-file-size').css('color', 'red');
                }
                else{
                    $('.upload-file-size').css('color', 'green');
                }
            }
            else{
                $('.upload-file-size').html('0 MB');
                $('.upload-file-size').css('color', 'green');
            }
        },150);
        
    });

    $('#ad_form').submit(function(e) {
        if(fileInput.get(0).files.length){
            var fileSize = fileInput.get(0).files[0].size; // in bytes
            if(fileSize>maxSize){
                console.log(maxSize);
                console.log(typeof maxSize);
                alert('Error! The file size is larger than ' + maxSize/Math.pow(10,6) + ' MB');
                return false;
            }
        }
        
    });
});
