$(document).ready(function() {
    console.log(arikaim.getBaseUrl());
    $('.tags-dropdown').dropdown({
        apiSettings: {
          // this url just returns a list of tags (with API response expected above)
          url: arikaim.getBaseUrl() + '/api/tags/list/{query}'
        },
        filterRemoteData: true
    });
});