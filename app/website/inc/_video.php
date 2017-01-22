<script>
var player = anm.Player.forSnapshot(
        'target', /* target tag ID */
        'video/index.html?a=0&r=0&c=1', /* snapshot URL */
        anm.importers.create('animatron') /* importer which can parse
           the given scene, in our case it is included in the bundle and
           named 'animatron'; its instance may be re-used */
        );

       var windowResize = function(){
           player._resize(window.innerWidth, window.innerHeight);
       }

       windowResize();
       window.onresize = windowResize;
</script>