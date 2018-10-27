import { PluginSidebar } from "@wordpress/editPost";
import { Fragment } from "@wordpress/element";
import {__} from "@wordpress/i18n";

const SidebarTC = () => (
    <PluginSidebar
        icon="twitter"
        name="tc-sidebar"
        title={__("Twitter Cards", "jm-tc")}
    >
        /*TODO*/
    </PluginSidebar>
);

export default SidebarTC;
