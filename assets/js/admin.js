
(function( $ ) {

  $(function() {

    //Add Language

    var $parent_container = $("#bbr_verified_review_meta_box");
    var $parent_container_repeater = $("#bbr_verified_review_meta_box .badge-criteria");

    $("#add").on("click", function(){
      var key = makeid(5);
      var content = "<div class='container'><input type='text' placeholder='Name' name='bbr_criteria["+key+"][name]' class='name'><input type='text'placeholder='%' name='bbr_criteria["+key+"][percent]' class='percent'> <button type='button' id='remove'>-</button></div>";
    	$parent_container_repeater.append(content);
    });

    $parent_container.on("click", "#remove", function(){
    	   $(this).parent().remove();
      	$("#add").show();
    });

   //Inster meida
    $parent_container.on("click", ".insert-my-media",open_media_window);
    $parent_container.on("click", ".remove-my-media",remove_media_bagde);
    function open_media_window() {
      $button = $(this);
      if (this.window === undefined) {
          this.window = wp.media({
                  title: 'Insert a media',
                  library: {type: 'image'},
                  multiple: false,
                  button: {text: 'Insert'}
              });

          var self = this; // Needed to retrieve our variable in the anonymous function below
          this.window.on('select', function() {
                  var first = self.window.state().get('selection').first().toJSON();
                  $button.parent().find('input.badge_image').val(first.id);
                  $button.parent().find('img').remove();
                  $('<img src="'+first.url+'" width="100" />').insertBefore($button);
          });
      }

      this.window.open();
      return false;

    }

    function remove_media_bagde(){
      $button = $(this);

      $('input.badge_image').val('');
      $('.insert-my-media').text('Upload Image');

      $button.parent().find('img').remove();
      $button.remove();

    }

    function makeid(length) {
        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < length; i++ ) {
          result += characters.charAt(Math.floor(Math.random() *
     charactersLength));
       }
       return result;
    }



  });
})(jQuery);
