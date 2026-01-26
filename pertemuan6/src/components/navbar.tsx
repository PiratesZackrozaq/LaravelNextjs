import Link from "next/link";

type NavbarProps = {
    menu_1: string;
    menu_2: string;
};

export default function Navbar({ menu_1, menu_2 }: NavbarProps) {
  return (
    <>
        <hr />
      <Link href="/" className="me-2">{menu_1}</Link>
      <Link href="/about" className="me-2">{menu_2}</Link>
    </>
  );
}
