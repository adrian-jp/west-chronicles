/**const routes = require('../../public/js/fos_js_routes.json');
 const Routing = require('../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js');
 Routing.setRoutingData(routes);
 export default Routing;*/

const router = require('../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router');
const routerConfig = require('../../public/js/fos_js_routes.json');
router.setRoutingData(routerConfig);

exports.routing = router;


