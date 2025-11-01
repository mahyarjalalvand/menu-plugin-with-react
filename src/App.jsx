import { useState } from "react";
import DrawerMenu from "./components/DrawerMenu";
import "./style.css";
function App({ slug }) {
  const [toggle, setToggle] = useState(false);

  const toggleMenu = () => setToggle((toggle) => !toggle);

  return (
    <div className="container pt-7 relative ">
      <span onClick={toggleMenu} className="*:size-9 w-fit cursor-pointer inline-block">
        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 50 50">
          <path d="M 0 7.5 L 0 12.5 L 50 12.5 L 50 7.5 Z M 0 22.5 L 0 27.5 L 50 27.5 L 50 22.5 Z M 0 37.5 L 0 42.5 L 50 42.5 L 50 37.5 Z"></path>
        </svg>
      </span>
      {toggle && <DrawerMenu setToggle={setToggle} menuSlug={slug} />}
    </div>
  );
}

export default App;
