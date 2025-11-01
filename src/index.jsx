import React from "react";
import ReactDOM from "react-dom";
import "./style.css";
import App from "./App";

document.querySelectorAll(".rms-menu").forEach((el) => {
  const props = JSON.parse(el.dataset.menu);
  ReactDOM.render(<App {...props} />, el);
});
