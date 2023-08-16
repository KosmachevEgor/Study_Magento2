define(['jquery', 'mage/url'], function ($, urlBuilder) {
    $.widget('mynamespace.widgetSearch', {
        options: {
            minChars: null,
            availableSku:
                [
                    'Simple2',
                    'Simple3',
                    'Simple4',
                    'Simple5'
                ],
            searchResultList: null,
            searchUrl: urlBuilder.build('search/ajax/suggest')
        },
        _create: function () {
            $(this.element).find('#search-input').on('keyup', this.processAutoComplete.bind(this));
            this.options.searchResultList = $(this.element).find('.search-result-list');
        },
        processAutoComplete: function (event) {
            var queryText = $(event.target).val();

            this.options.searchResultList.empty();

            if (queryText.length >= this.options.minChars) {
                //Use Ajax request
                $.getJSON(
                    this.options.searchUrl,
                    {q: queryText},
                    function (data) {
                        if (data.length) {
                            var searchList = data.map(function (item) {
                                return $('<li/>').text(item.title + " [" + item.num_results + "]");
                            });

                            this.options.searchResultList.append(searchList);
                        } else {
                            this.options.searchResultList.empty();
                        }
                    }.bind(this)
                );

                /*Without Ajax request
                var filteredSku = this.options.availableSku.filter(function (item) {
                    return item.indexOf(queryText) !== -1;
                });
                if(filteredSku.length) {
                    var searchList = filteredSku.map(function (item){
                        return $('<li/>').text(item);
                    });

                    this.options.searchResultList.append(searchList);
                } else {
                    this.options.searchResultList.empty();
                }*/
            }
        }
    });

    return $.mynamespace.widgetSearch;
})
