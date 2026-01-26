import Navbar from '@/components/navbar'

export default function Home () {
  return (
    <div className='container pt-3'>
      <h1>About</h1>
      <div className='d-flex gap-3'></div>
      <Navbar menu_1='Home' menu_2='About' />
    </div>
  )
}
