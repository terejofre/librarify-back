var CommentApp = {
    initialize: function($wrapper) {
        this.$wrapper = $wrapper;

        this.$wrapper.find('.js-delete-comment').on(
            'click',
            this.handleCommentDelete.bind(this)
        );
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