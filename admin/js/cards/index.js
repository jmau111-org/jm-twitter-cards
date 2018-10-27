import "./style.scss";

import { Fragment } from "@wordpress/element";
import { registerPlugin } from "@wordpress/plugins";

import SidebarTC from "./components/sidebar";

const TC = () => (
    <Fragment>
        <SidebarTC />
    </Fragment>
);

registerPlugin("tc", {
    render: TC
});