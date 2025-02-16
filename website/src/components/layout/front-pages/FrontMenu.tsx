'use client'

// React Imports
import { useEffect } from 'react'

// Next Imports
import { usePathname } from 'next/navigation'
import Link from 'next/link'

// MUI Imports
import Typography from '@mui/material/Typography'
import Drawer from '@mui/material/Drawer'
import useMediaQuery from '@mui/material/useMediaQuery'
import type { Theme } from '@mui/material/styles'
import IconButton from '@mui/material/IconButton'

// Third-party Imports
import classnames from 'classnames'

// Type Imports
import type { Mode } from '@core/types'

// Component Imports
import FrontMenuDropDown from './FrontMenuDropDown'

// import axios from 'axios'
// import authConfig from '@configs/auth'

import { HeaderMenusList } from './MenusData'

type Props = {
  mode: Mode
  isDrawerOpen: boolean
  setIsDrawerOpen: (open: boolean) => void
}

type WrapperProps = {
  children: React.ReactNode
  isBelowLgScreen: boolean
  className?: string
  isDrawerOpen: boolean
  setIsDrawerOpen: (open: boolean) => void
}

const Wrapper = (props: WrapperProps) => {
  // Props
  const { children, isBelowLgScreen, className, isDrawerOpen, setIsDrawerOpen } = props

  if (isBelowLgScreen) {
    return (
      <Drawer
        variant='temporary'
        anchor='left'
        open={isDrawerOpen}
        onClose={() => setIsDrawerOpen(false)}
        ModalProps={{
          keepMounted: true
        }}
        sx={{ '& .MuiDrawer-paper': { width: ['100%', 300] } }}
        className={classnames('p-5', className)}
      >
        <div className='p-4 flex flex-col gap-x-3'>
          <IconButton onClick={() => setIsDrawerOpen(false)} className='absolute inline-end-4 block-start-2'>
            <i className='ri-close-line' />
          </IconButton>
          {children}
        </div>
      </Drawer>
    )
  }

  return <div className={classnames('flex items-center flex-wrap gap-x-2 gap-y-3', className)}>{children}</div>
}

const FrontMenu = (props: Props) => {
  // Props
  const { isDrawerOpen, setIsDrawerOpen, mode } = props

  // Hooks
  const pathname = usePathname()
  const isBelowLgScreen = useMediaQuery((theme: Theme) => theme.breakpoints.down('lg'))

  useEffect(() => {
    if (!isBelowLgScreen && isDrawerOpen) {
      setIsDrawerOpen(false)
    }
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [isBelowLgScreen])

  // useEffect(() => {
  //   getHeaderMenusList()
  // }, [])
  // const [headerMenus, setHeaderMenus] = useState<any[]>([])
  // const getHeaderMenusList = async function () {
  //   try {
  //     const RS = await axios.get(authConfig.backEndApiHost + 'website/menu.php', {
  //       headers: {
  //         'Content-Type': 'application/json'
  //       }
  //     }).then(res=>res.data)
  //     setHeaderMenus(RS)
  //   }
  //   catch(Error: any) {
  //       console.log("getChatLogList Error", Error)
  //   }
  // }

  return (
    <Wrapper isBelowLgScreen={isBelowLgScreen} isDrawerOpen={isDrawerOpen} setIsDrawerOpen={setIsDrawerOpen}>
      {HeaderMenusList && HeaderMenusList.map((Item: any, Index: number)=>{

        if(Item.children && Item.children.length > 0)  {
          return (
            <FrontMenuDropDown key={Index}
              mode={mode}
              isBelowLgScreen={isBelowLgScreen}
              isDrawerOpen={isDrawerOpen}
              setIsDrawerOpen={setIsDrawerOpen}
              MenuInfor={Item}
            />
          )
        }
        else {
          return (
            <Typography key={Index}
              component={Link}
              href={Item.target}
              className={classnames('font-medium hover:text-primary', { 'text-primary': Item.default && pathname === '/home' })}
              color='text.primary'
            >
              {Item.title}
            </Typography>
          )
        }
      })}
    </Wrapper>
  )
}

export default FrontMenu
