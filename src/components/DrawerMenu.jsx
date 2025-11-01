import React, { useState, useEffect } from "react";

function DrawerMenu({ setToggle, menuSlug }) {
  const [items, setItems] = useState([]);
  useEffect(() => {
    if (!menuSlug) return;
    const url = new URL(RMS_DATA.rest_url);
    url.searchParams.append("slug", menuSlug);
    fetch(url)
      .then((res) => res.json())
      .then((res) => setItems(res))
      .catch((err) => console.log(err));
  }, [menuSlug]);
  console.log(items);
  const closeHandler = () => setToggle((prev) => !prev);
  return (
    <div className="fixed inset-0 bg-black/70">
      <div className="w-1/4 bg-white h-full relative px-3 pt-10">
        <span className="w-full [&>i]:*:size-5 [&>i]:cursor-pointer flex items-center justify-end p-2">
          <i onClick={closeHandler}>
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 50 50">
              <path d="M 9.15625 6.3125 L 6.3125 9.15625 L 22.15625 25 L 6.21875 40.96875 L 9.03125 43.78125 L 25 27.84375 L 40.9375 43.78125 L 43.78125 40.9375 L 27.84375 25 L 43.6875 9.15625 L 40.84375 6.3125 L 25 22.15625 Z"></path>
            </svg>
          </i>
        </span>
        <ul>
          {items.map((item) => (
            <li key={item.ID}>
              <a href={item.url}>{item.title}</a>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}

export default DrawerMenu;
