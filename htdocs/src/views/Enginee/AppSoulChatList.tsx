// ** React Imports
import { Fragment } from 'react'

// ** MUI Imports
import Box from '@mui/material/Box'
import Grid from '@mui/material/Grid'
import Button from '@mui/material/Button'
import CardMedia from '@mui/material/CardMedia'
import Dialog from '@mui/material/Dialog'
import DialogContent from '@mui/material/DialogContent'
import Typography from '@mui/material/Typography'

import Avatar from '@mui/material/Avatar'
import Container from '@mui/material/Container'
import CircularProgress from '@mui/material/CircularProgress'
import { useTheme } from '@mui/material/styles'

// ** Icon Imports
import Icon from 'src/@core/components/icon'

import { useTranslation } from 'react-i18next'

const AppSoulChatList = (props: any) => {
  // ** Hook
  const { t } = useTranslation()
  const theme = useTheme()

  // ** Props
  const {
    store,
    authConfig,
    loading,
    loadingText,
    show,
    setShow,
    setAddEditActionId,
    setViewActionOpen,
    setEditViewCounter,
    setAddEditActionName,
    setAddEditActionOpen,
    isMobileData,
    setCSRF_TOKEN
  } = props

  const renderContent = () => {
      return (
        <Grid container>
          <Grid item xs={12} sx={{ height: '100%', overflowY: 'auto', scrollbarWidth: 'thin', scrollbarColor: '#ffffff' }}>
              <Fragment>
                {isMobileData == false && (
                  <Grid container spacing={2}>
                    <Grid item xs={12} sx={{ml: 2, mt: 2}}>
                      <Box px={2} pb={0}>
                        <Typography variant="h6">心理健康测评</Typography>
                      </Box>
                    </Grid>
                  </Grid>
                )}
                <Grid container spacing={2} sx={{ my: 0}}>
                  {store.init_default.data && store.init_default.data.map((item: any, index: number) => (
                    <Grid item key={index} xs={12} sm={6} md={4} lg={4}>
                      <Box position="relative" sx={{ml: 4}}>
                        <CardMedia image={`/images/cardmedia/cardmedia-${theme.palette.mode}.png`} sx={{ height: '14rem', objectFit: 'contain', borderRadius: 1 }}/>
                        <Box position="absolute" top={10} left={3} m={1} px={0.8} borderRadius={1} >
                          <Box display="flex" alignItems="center">
                          <Avatar src={item.avatar ? authConfig.backEndApiHost + "/" + item.avatar : '/images/avatars/'+(item.id2 % 8 + 1)+'.png'} sx={{ mr: 3, width: 35, height: 35 }} />
                            <Typography
                                sx={{
                                    fontWeight: 500,
                                    lineHeight: 1.71,
                                    letterSpacing: '0.22px',
                                    fontSize: '1rem !important',
                                    overflow: 'hidden',
                                    textOverflow: 'ellipsis',
                                    whiteSpace: 'nowrap',
                                    flexGrow: 1,
                                }}
                            >
                                {item.测评名称}
                            </Typography>
                          </Box>
                        </Box>
                        <Box position="absolute" top={55} left={5} m={1} px={0.8} borderRadius={1}
                          sx={{
                            overflow: 'hidden',
                            display: '-webkit-box',
                            WebkitLineClamp: 5,
                            WebkitBoxOrient: 'vertical',
                          }}
                          >
                          <Typography variant='caption'>{item.测评说明}</Typography>
                        </Box>
                        <Box position="absolute" bottom={0} left={1} m={1} px={0.8}>
                          <Button
                            variant="text"
                            size="small"
                            startIcon={<Icon icon={item.permission == 'private' ? 'ri:git-repository-private-line' : 'material-symbols:share'} />}
                            onClick={()=>{
                              setAddEditActionId(item.id)
                              setViewActionOpen(true)
                              setEditViewCounter(0)
                              setCSRF_TOKEN(store.init_default.CSRF_TOKEN)
                              setAddEditActionName('view_default')
                            }}
                          >
                            {'查看结果'}
                          </Button>
                        </Box>
                        <Box position="absolute" bottom={0} right={1} m={1} px={0.8}>
                          <Button variant="text" size="small" startIcon={<Icon icon='material-symbols:chat-outline' />}
                            onClick={()=>{
                              setAddEditActionId(item.id)
                              setAddEditActionOpen(true)
                              setEditViewCounter(0)
                              setCSRF_TOKEN(store.init_default.CSRF_TOKEN)
                              setAddEditActionName('edit_default')
                            }}
                            >
                            {'开始测评'}
                          </Button>
                        </Box>
                      </Box>
                    </Grid>
                  ))}
                  {store.init_default.data && store.init_default.data.length == 0 && loading == false?
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

export default AppSoulChatList
