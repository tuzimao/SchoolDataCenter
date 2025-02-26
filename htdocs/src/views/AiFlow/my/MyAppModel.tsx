// ** React Imports
import { useState, MouseEvent, Fragment } from 'react'

// ** MUI Imports
import Box from '@mui/material/Box'
import Card from '@mui/material/Card'
import Grid from '@mui/material/Grid'
import Button from '@mui/material/Button'
import CardMedia from '@mui/material/CardMedia'
import Dialog from '@mui/material/Dialog'
import DialogContent from '@mui/material/DialogContent'
import Typography from '@mui/material/Typography'
import IconButton from '@mui/material/IconButton'
import Menu from '@mui/material/Menu'
import MenuItem from '@mui/material/MenuItem'
import Link from 'next/link'

import Container from '@mui/material/Container'
import CircularProgress from '@mui/material/CircularProgress'
import { useTheme } from '@mui/material/styles'
import { useRouter } from 'next/router'

// ** Icon Imports
import Icon from 'src/@core/components/icon'

import { useTranslation } from 'react-i18next'


const AppModel = (props: any) => {
  // ** Hook
  const { t } = useTranslation()
  const theme = useTheme()
  const router = useRouter()

  // ** Props
  const {
    app,
    loading,
    loadingText,
    setAppId,
    show,
    setShow,
    setDeleteOpen,
    setNewOpen
  } = props

  const RowOptions = (props: any) => {
    const { id } = props

    const [anchorEl, setAnchorEl] = useState<null | HTMLElement>(null)
  
    const rowOptionsOpen = Boolean(anchorEl)
  
    const handleRowOptionsClick = (event: MouseEvent<HTMLElement>) => {
      setAnchorEl(event.currentTarget)
    }
    const handleRowOptionsClose = () => {
      setAnchorEl(null)
    }
  
    return (
      <>
        <IconButton size='small' onClick={handleRowOptionsClick} >
          <Icon icon='mdi:dots-vertical' />
        </IconButton>
        <Menu
          keepMounted
          anchorEl={anchorEl}
          open={rowOptionsOpen}
          onClose={handleRowOptionsClose}
          anchorOrigin={{
            vertical: 'bottom',
            horizontal: 'right'
          }}
          transformOrigin={{
            vertical: 'top',
            horizontal: 'right'
          }}
          PaperProps={{ style: { minWidth: '8rem' } }}
        >
          <MenuItem
            component={Link}
            sx={{ '& svg': { mr: 2 } }}
            onClick={handleRowOptionsClose}
            href={`/flow/edit/${id}`}
          >
            <Icon icon='mdi:pencil-outline' fontSize={20} />
            {t('Edit')}
          </MenuItem>
          <MenuItem
            component={Link}
            sx={{ '& svg': { mr: 2 } }}
            onClick={handleRowOptionsClose}
            href={`/flow/chat/${id}`}
          >
            <Icon icon='material-symbols:chat-bubble-outline' fontSize={20} />
            {t('Chat')}
          </MenuItem>
          <MenuItem onClick={()=>{
            setAppId(id)
            setDeleteOpen(true)
            }} 
            sx={{ '& svg': { mr: 2 } }}>
            <Icon icon='mdi:delete-outline' fontSize={20} />
            {t('Delete')}
          </MenuItem>
        </Menu>
      </>
    )
  }

  const renderContent = () => {
      return (
        <Grid container spacing={2}>
          <Grid item xs={12} sx={{ height: '100%', overflowY: 'auto', scrollbarWidth: 'thin', scrollbarColor: '#ffffff' }}>
            <Card sx={{ px: 3, pt: 1}}>
              {true ? (
                <Fragment>
                  <Grid container spacing={2}>
                    <Grid item xs={6}>
                      <Box p={2}>
                        <Typography variant="h6">{t('My App')}</Typography>
                      </Box>
                    </Grid>
                    <Grid item xs={6}>
                      <Box p={2} display="flex" justifyContent="flex-end">
                        <Button variant="contained" color="primary" size="small" startIcon={<Icon icon='mdi:add' />} onClick={()=>{setNewOpen(true)}}>
                        {t('New App')}
                        </Button>
                      </Box>
                    </Grid>
                  </Grid>
                  <Grid container spacing={2} sx={{ mt: 2, mb: 2}}>
                    {app && app.map((item: any, index: number) => (
                      <Grid item key={index} xs={12} sm={6} md={4} lg={4}>
                        <Box position="relative" sx={{mb: 2, mr: 2}}>
                          <CardMedia image={`/images/cardmedia/cardmedia-${theme.palette.mode}.png`} sx={{ height: '11.25rem', objectFit: 'contain', borderRadius: 1 }}/>
                          <Box position="absolute" top={10} left={5} m={1} px={0.8} borderRadius={1}
                            onClick={()=>{
                              router.push('/flow/edit/' + item._id)
                            }}
                            sx={{cursor: 'pointer'}}
                          >
                            <Box display="flex" alignItems="center">
                              <Icon icon={item.AppAvatar} color={theme.palette.primary.main} fontSize={35} />
                              <Typography 
                                  sx={{
                                      fontWeight: 500,
                                      lineHeight: 1.71,
                                      letterSpacing: '0.22px',
                                      fontSize: '1rem !important',
                                      maxWidth: '220px',
                                      overflow: 'hidden',
                                      textOverflow: 'ellipsis',
                                      whiteSpace: 'nowrap',
                                      flexGrow: 1,
                                      pl: 1
                                  }}
                              >
                                  {item.AppName}
                              </Typography>
                            </Box>
                          </Box>
                          <Box position="absolute" top={0} right={0} m={1} px={0.8} borderRadius={1}>
                            <RowOptions id={item._id} />
                          </Box>
                          <Box position="absolute" top={55} left={5} m={1} px={0.8} borderRadius={1} 
                            sx={{ 
                              cursor: 'pointer',
                              overflow: 'hidden',
                              display: '-webkit-box',
                              WebkitLineClamp: 3,
                              WebkitBoxOrient: 'vertical',
                            }}
                            onClick={()=>{
                              router.push('/flow/edit/' + item._id)
                            }}
                            >
                            <Typography variant='body2'>{item.AppIntro}</Typography>
                          </Box>
                          <Box position="absolute" bottom={0} left={1} m={1} px={0.8}>
                            <Button  variant="text" size="small" disabled sx={{textTransform: 'capitalize'}} startIcon={<Icon icon={item.IsPublic == '否' ? 'ri:git-repository-private-line' : 'material-symbols:share'} />} >
                              {t(item.permission)}
                            </Button>
                          </Box>
                          <Box position="absolute" bottom={0} right={1} m={1} px={0.8}>
                            <Button  variant="text" size="small" startIcon={<Icon icon='material-symbols:chat-outline' />} >
                              {t('Chat')}
                            </Button>
                          </Box>
                        </Box>
                      </Grid>
                    ))}
                    {app && app.length == 0 && loading == false?
                    <Grid 
                      item 
                      key='0' 
                      xs={12}
                      sx={{
                        textAlign: 'center',
                        marginTop: '1rem',
                        fontSize: '1.2rem',
                        fontWeight: 'bold',
                        color: 'gray',
                      }}
                    >
                      <Typography variant="body1">{t('No Data')}</Typography>
                    </Grid>
                    :
                    null}
                  </Grid>
                </Fragment>
              ) : (
                <Fragment></Fragment>
              )}
            </Card>
            {loading ?
            <Dialog
              open={show}
              onClose={() => setShow(false)}
            >
              <DialogContent sx={{ position: 'relative' }}>
                <Container>
                  <Grid container spacing={2}>
                    <Grid item xs={8} sx={{}}>
                      <Box sx={{ ml: 6, display: 'flex', alignItems: 'center', flexDirection: 'column', whiteSpace: 'nowrap' }}>
                        <CircularProgress sx={{ mb: 4 }} />
                        <Typography>{t(loadingText) as string}</Typography>
                      </Box>
                    </Grid>
                  </Grid>
                </Container>
              </DialogContent>
            </Dialog>
            :
            null}
          </Grid>
        </Grid>
      )
  }

  return renderContent()
}

export default AppModel
