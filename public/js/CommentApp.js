var CommentApp = {
    initialize: function($wrapper) {
        this.$wrapper = $wrapper;

        this.$wrapper.find('js-delete-comment').on(
            'click',
            this.handleCommentDelete.bind(this)
        );

        this.$wrapper.find('js-comment-form').on(
            'submit',
            this.handleCommentCreation.bind(this)
        )

    },

    handleCommentCreation: function(e) {
      e.preventDefault();

      var $form = $(e.currentTarget);
      var creationUrl = $form.attr('action');
        console.log('submiting');
      /*$.ajax({
          url: creationUrl,
          method: 'POST',
          data: $form.serialize(),
          success: function(data) {
            console.log(data);
          }
      });*/

    },

    handleCommentDelete: function(e) {
        e.preventDefault();

        var $link = $(e.currentTarget)
        var deleteUrl = $link.data('url');
        var $row = $link.closest('div');

        $.ajax({
            url: deleteUrl,
            method: 'DELETE',
            success: function(data) {
                $row.fadeOut();
            }
        })
    }
};