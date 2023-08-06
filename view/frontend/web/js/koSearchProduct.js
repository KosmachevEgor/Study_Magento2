define(['uiComponent'], function (Component) {
    return Component.extend({
        defaults: {
            searchText: "",
            searchResult: [],
            availableSku: [
                'SimpleProd1',
                'Simple2',
                'Simple3',
                'Simple4',
                'Simple5'
            ]
        },
        initObservable: function () {
            this._super();
            this.observe(['searchText','searchResult']);
            return this;
        },
        initialize: function () {
            this._super();
            this.searchText.subscribe(this.getSearchResult.bind(this));
        },
        getSearchResult: function (searchValue) {
            if(searchValue.length >=3) {
                var filterSearch = this.availableSku.filter(
                    function (item) {
                        return item.indexOf(searchValue) !== -1;
                    }
                );
                this.searchResult(filterSearch);
            } else {
                this.searchResult([]);
            }
        }
    });
});
