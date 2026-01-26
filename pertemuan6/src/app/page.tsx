"use client";

import Navbar from "@/components/navbar";
import { useState, useEffect } from "react";

export default function Home() {
  const [count, setCount] = useState(0)

  useEffect(() => {
    alert("Welcome to the Next.js course!");
  }, []);
  useEffect(() => {
    alert("count changed to " + count);
  }, [count]);

  return (
    <div className="container pt-3">
      <h1>Hello World</h1>
      <div className="d-flex gap-3">
        <button className="btn btn-primary" onClick={() => setCount(count + 1)}>+</button>
        <div className="bg-info rounded px-3 text-white fw-bold d-flex align-items-center">{count}</div>
        <button className="btn btn-danger" onClick={() => setCount(count - 1)}>-</button>
      </div>
      <Navbar menu_1="Home" menu_2="About" />
    </div>
  );
}
