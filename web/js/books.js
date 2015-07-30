/**
 * Created by Cranky4 on 30.07.2015.
 */
$(function () {

    //модалка для книг
    $(document).on("click", "a[rel='book_view']", function () {
        var $btn = $(this);
        var $modal = $("#view-book-modal");
        var $contentHolder = $modal.find(".content_holder");

        //загрузка контента
        $contentHolder.load($btn.attr("href"));
        //показ модалки
        $modal.modal("show");

        return false;
    });

});