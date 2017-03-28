/*
 *
 * @name		ajaxMultiFileUpload
 * @author		Kevin Crossman
 * @contact		kevincrossman@gmail.com
 * @version		2.1
 * @date			Oct 14 2008
 * @type    	 	jQuery
 *
*/
; (function($) {

    $.fn.extend({
        ajaxMultiFileUpload: function(options) { 
        	opt = $.extend({}, $.uploadSetUp.defaults, options);
            if (opt.file_types.match('jpg') && !opt.file_types.match('jpeg')) 
            	opt.file_types += ',jpeg';
            $this = $(this);
            new $.uploadSetUp();
        }
    });

    $.uploadSetUp = function() {
        $('body').append($('<div></div>').append($('<iframe src="about:blank" id="myFrame" name="myFrame"></iframe>')));
        $this.append($('<form target="myFrame" enctype="multipart/form-data" action="' + opt.ajaxFile + '" method="post" name="myUploadForm" id="myUploadForm"><input type="hidden" name="max_photos" value="' + opt.maxNumFiles + '" /></form>')
            .append(
		$('<h2 class="numFiles"></h2>'),
		$('<div class="select" title="upload new picture"></div>').append($('<input id="myUploadFile" class="myUploadFile file" type="file" value="" name="upload_pic" size="1"/>')),
		$('<h2 class="upload">'+opt.click_to_upload+'</h2>'),
		$('<ul id="ul_files"></ul>'),
		$('<ul id="response"></ul>')
		));

        init();

    };

    function init() {
	// show old photos
	var photos=opt.crt_photos.split(";");
	var no = photos.length;
	for(f=0; f< no-1; f++) {

		var a = photos[f];
		eval(a);
		uploadImage(a);

	}

        // if file type is allowed, submit form
        $('#myUploadFile').livequery('change', function() { 
        	if (checkFileType(this.value))
        		$('#myUploadForm').submit(); 
        });
        // execute event.submit when form is submitted
        $('#myUploadForm').submit(function() { 

        	return event.submit(this); 
        });
        // delete uploaded file
        $(".delete").livequery('click', function() {
            // avoid duplicate function call
            $(this).unbind('click');
		_delete($(this));
        });

        $(".orderUp").livequery('click', function() {
            $(this).unbind('click');
		_orderUp($(this));
        });

        $(".orderDown").livequery('click', function() {
            $(this).unbind('click');
		_orderDown($(this));
        });

        // function to handle form submission using iframe
	var event = {
            // setup iframe
            frame: function(_form) {
                $("#myFrame")
                	.empty()
                	.one('load',  function() { event.loaded(this, _form) });
            },
            // call event.submit after submit
            submit: function(_form) {
                $('.select').addClass('waiting');
                event.frame(_form);
            },
            // display results from submit after loades into iframe
            loaded: function(id, _form) {
                var d = frametype(id),
                data = d.body.innerHTML.replace(/^\s+|\s+$/g, '');
//alert(data);

                eval(data);

                $('.select.waiting').removeClass('waiting');

                // if no problem reported from submit
                if (typeof pst === 'undefined') {

                    var problem = '';
                    if (data.length) problem = data;
                    else problem = 'There was no response from the server.';
                    $('UL#response').text(problem);
		    $('UL#response').fadeIn();
                } 
                else if (!pst.problem) {
			uploadImage(pst);
			$('UL#response').fadeOut();
                } 
                else {

		var problem = '';
		if (pst.problem.error) problem = pst.problem.error;
		$('UL#response').text(problem);
		$('UL#response').fadeIn();
                }
            }
        };

	function uploadImage(pst) {
                    var _img = new Image(),

                    $delete = $('<div id="' + pst.img.rename + '" class="delete" title="delete file"></div>');

                    // add remove icon
                    $new = $('<div class="fileInfo"></div>').append($delete);
                    // add info wrapper	
                    //$name = $('<div class="nameOfFile">' + pst.img.name + '</div>'); // add name of file
                    // store names for ajax delete
                    $delete[0]._name = pst.img.name;
                    $delete[0]._rename = pst.img.rename;
                   //alert(pst.img.src);
                   // setup image
                    $(_img)
                    	.attr({ src: pst.img.src, alt: pst.img.alt, width: pst.img.width, height: pst.img.height, title: pst.img.name })
                    	.addClass('uploaded');

                    // display thumbname and info	
                    $("UL#ul_files").append($('<LI></LI>')
			.append('<span name="' + pst.img.name + '" class="orderUp"><img src="'+opt.template_path+'images/order-up.gif" title="'+opt.order_up+'"></span>&nbsp;&nbsp;&nbsp;&nbsp;<span name="' + pst.img.name + '" class="orderDown"><img src="'+opt.template_path+'images/order-down.gif" title="'+opt.order_down+'"></span>')
			.append($new.prepend($(_img))));

                    // update file counter
                    updateCount();

	};

	// delete
        function _delete(toDelete) {
            $.post(opt.ajaxFile, { deleteFile: toDelete[0]._rename, origName: toDelete[0]._name, upload: opt.uploadFolder, thumb: opt.thumbFolder, mode: opt.mode },  
            	function(returned) {
            		//$('UL#response').append('<li>' + returned.replace(/^\s+|\s+$/g, '') + '</li>');
               	 	toDelete
                		.parents('LI')
                		.fadeOut(1000, function(){ $(this).remove(); updateCount() });
            	});

       };

        function _orderUp(toOrder) {

            $.post(opt.ajaxFile, { orderUp: toOrder.attr("name") },  
		function(returned) {
			if(returned!=0) {

				opt.crt_photos = returned;
				$("UL#ul_files").html('');

				// show old photos
				var photos=opt.crt_photos.split(";");
				var no = photos.length;
				for(f=0; f< no-1; f++) {

					var a = photos[f];
					eval(a);
					uploadImage(a);
				}
			}
            	});
        };

        function _orderDown(toOrder) {

            $.post(opt.ajaxFile, { orderDown: toOrder.attr("name") },  
		function(returned) {
			if(returned!=0) {

				opt.crt_photos = returned;
				$("UL#ul_files").html('');

				// show old photos
				var photos=opt.crt_photos.split(";");
				var no = photos.length;
				for(f=0; f< no-1; f++) {

					var a = photos[f];
					eval(a);
					uploadImage(a);
				}
			}
            	});
	};
        // update the file counter
        function updateCount() {
		var numUploads = $("UL#ul_files").children('LI').size();

		if(numUploads == opt.maxNumFiles) {
			$("H2.numFiles").text(numUploads + opt.files_uploaded + opt.maxNumFiles + opt.allowed + opt.limit_reached);
			$('.select').css({ opacity: 0 });
		} else {
			$("H2.numFiles").text(numUploads + opt.files_uploaded + opt.maxNumFiles + opt.allowed);
			$('.select').css({ opacity: 1 });
		}
        };
        // check if file extension is allowed
        function checkFileType(file_) {

            var ext_ = file_.toLowerCase().substr(file_.toLowerCase().lastIndexOf('.') + 1);
            if (!opt.file_types.match(ext_)) {
                alert('file type ' + ext_ + ' not allowed');
                return false;
            } 
            else return true;
        };
        // check type of iframe
        function frametype(fid) {
            return (fid.contentDocument) ? fid.contentDocument: (fid.contentWindow) ? fid.contentWindow.document: window.frames[fid].document;
        };

        updateCount();
    }

})(jQuery);