$(document).ready(function()
{
    /**
     * Отправка запроса на поиск торрента
     * в форме сверху.
     */
    $('.form-search').on("submit", function()
    {
        searchString = $('.form-search .search-query').val();

        searchUrl = "/search/"+searchString;

        $(this).attr("action", searchUrl);
        $(this).submit();
    });
});