jQuery(document).ready(function(e) {
    function a() {
        e("#cardPlayer").parents(":eq(1)").hide(), e("#cardPlayerWidth").parents(":eq(1)").hide(), e("#cardPlayerHeight").parents(":eq(1)").hide(), e("#cardPlayerStream").parents(":eq(1)").hide(), e("#cardPlayerCodec").parents(":eq(1)").hide()
    }

    function r() {
        e("#cardPlayer").parents(":eq(1)").show(), e("#cardPlayerWidth").parents(":eq(1)").show(), e("#cardPlayerHeight").parents(":eq(1)").show(), e("#cardPlayerStream").parents(":eq(1)").show(), e("#cardPlayerCodec").parents(":eq(1)").show()
    }
    e("#twitterCardType").on("change", function() {
        "player" == this.value ? r() : a()
    }).change()
});