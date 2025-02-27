// ** React Imports
import { Fragment, useEffect, ReactNode } from 'react'

// ** MUI Imports
import Box from '@mui/material/Box'
import Button from '@mui/material/Button'
import Card from '@mui/material/Card'
import Grid from '@mui/material/Grid'
import TextField from '@mui/material/TextField'
import Typography from '@mui/material/Typography'
import CardContent from '@mui/material/CardContent'
import Select from '@mui/material/Select'
import MenuItem from '@mui/material/MenuItem'
import InputLabel from '@mui/material/InputLabel'
import FormControl from '@mui/material/FormControl'
import TextField2 from 'src/views/Enginee/components/TextField2'

// ** Axios Imports
import { useRouter } from 'next/router'

// ** Icon Imports
import Icon from 'src/@core/components/icon'

// ** Third Party Import
import { useTranslation } from 'react-i18next'
import { useAuth } from 'src/hooks/useAuth'
import { CheckPermission } from 'src/functions/ChatBook'
import PerfectScrollbar from 'react-perfect-scrollbar'
import SimpleEditApplication from 'src/views/AiFlow/edit/SimpleEditApplication'
import SimpleEditAppDelete from 'src/views/AiFlow/edit/SimpleEditAppDelete'

const WorkFlowPermissionList = ['private', 'public']

const ScrollWrapper = ({ children, hidden }: { children: ReactNode; hidden: boolean }) => {
    if (hidden) {
      return <Box sx={{ height: '100%', overflowY: 'auto', overflowX: 'hidden' }}>{children}</Box>
    } else {
      return <PerfectScrollbar options={{ wheelPropagation: false, suppressScrollX: true }}>{children}</PerfectScrollbar>
    }
}

const SimpleEdit = (props: any) => {
  // ** Props
  const { app, setApp, handleEditApp, handleDeleteApp, isDisabledButton, deleteOpen, setDeleteOpen } = props
  
  // ** Hook
  const { t } = useTranslation()
  const auth = useAuth()
  const router = useRouter()
  useEffect(() => {
    CheckPermission(auth, router, false)
  }, [])


  const hidden = false;
  
  return (
    <Fragment>
        <Box sx={{ p: 0, position: 'relative', overflowX: 'hidden', height: 'calc(100% - 0.25rem)' }}>
            <ScrollWrapper hidden={hidden}>
                {auth.user && auth.user.id ?
                <Grid display="flex" flexDirection="column" sx={{py: 2}}>
                    <Grid container spacing={2}>
                        <Card sx={{ border: theme => `1px solid ${theme.palette.divider}`, my: 1, ml: 3, mr: 3, p: 2 }}>
                            <CardContent>
                                <Grid container spacing={5}>
                                    <Grid xs={12} sx={{m: 0, p: 0, zIndex: 999}}>
                                        <Typography sx={{ fontSize: '0.8rem', textAlign: 'right' }}>
                                            Id: {app.id.slice(0, 20)}...
                                        </Typography>
                                    </Grid>
                                    <Grid item xs={12} sx={{m:0, mt: -4, p: 0}}>
                                        <Typography variant='h6' sx={{ ml: 0, mb: 0 }}>
                                            {`${t(app?.name || '')}`}
                                        </Typography>
                                    </Grid>

                                    <Grid item xs={12}>
                                        <Button variant='outlined' sx={{mr: 1}} size="small" startIcon={<Icon icon='mingcute:file-export-fill' />} onClick={()=>{
                                            router.push('/flow/chat/' + app.id)
                                        }}>
                                        {t("Chat")}
                                        </Button>
                                        <Button variant='outlined' sx={{mr: 1}} size="small" startIcon={<Icon icon='material-symbols:chat' />} onClick={()=>{
                                            router.push('/flow/publish/' + app.id)
                                        }}>
                                        {t("Publish")}
                                        </Button>
                                        <Button variant='contained' sx={{mr: 1}} size="small" startIcon={<Icon icon='material-symbols:save' />}onClick={()=>{
                                            setDeleteOpen(true)
                                        }}>
                                        {t("Delete")}
                                        </Button>
                                    </Grid>
                                    <Grid item xs={4}>
                                        <TextField
                                            fullWidth
                                            size="small"
                                            label={`${t('Avatar')}`}
                                            placeholder={`${t('Avatar')}`}
                                            value={app?.avatar || ''}
                                            onChange={(e: any) => {
                                                setApp((prevState: any)=>({
                                                    ...prevState,
                                                    avatar: e.target.value as string
                                                }))
                                                console.log("e.target.value", e.target.value);
                                            }}
                                        />
                                    </Grid>
                                    <Grid item xs={4}>
                                        <TextField
                                            fullWidth
                                            size="small"
                                            label={`${t('Name')}`}
                                            placeholder={`${t('Name')}`}
                                            value={app?.name || ''}
                                            onChange={(e: any) => {
                                                setApp((prevState: any)=>({
                                                    ...prevState,
                                                    name: e.target.value as string
                                                }))
                                                console.log("e.target.value", e.target.value);
                                            }}
                                        />
                                    </Grid>
                                    <Grid container item xs={4} alignItems="center">
                                        <FormControl sx={{ mr: 0 }}>
                                            <InputLabel >{t("Permission")}</InputLabel>
                                            <Select 
                                                size="small" 
                                                label={t("Permission")}
                                                value={app?.permission || 'private'} 
                                                fullWidth
                                                onChange={(e: any) => {
                                                    setApp((prevState: any)=>({
                                                        ...prevState,
                                                        permission: e.target.value as string
                                                    }))
                                                    console.log("e.target.value", e.target.value);
                                                }}
                                                >
                                                {WorkFlowPermissionList.map((item: any, indexItem: number)=>{
                                                    return <MenuItem value={item} key={`${indexItem}`}>{t(item) as string}</MenuItem>
                                                })}
                                            </Select>
                                        </FormControl>
                                    </Grid>
                                    <Grid container item xs={12} alignItems="center">
                                        <TextField2
                                            fullWidth
                                            multiline
                                            rows={4}
                                            size="small"
                                            label={`${t('Intro')}`}
                                            placeholder={`${t('Intro')}`}
                                            value={app?.intro || ''}
                                            onChange={(e: any) => {
                                                setApp((prevState: any)=>({
                                                    ...prevState,
                                                    intro: e.target.value as string
                                                }))
                                                console.log("e.target.value", e.target.value);
                                            }}
                                        />
                                    </Grid>
                                    
                                    <Grid item xs={12} container justifyContent="flex-end">
                                        <Button type='submit' variant='contained' size='small' onClick={()=>{handleEditApp()}} disabled={isDisabledButton} >
                                            {t('Submit')}
                                        </Button>
                                    </Grid>

                                </Grid>
                            </CardContent>
                        </Card>
                    </Grid>
                    { app ? 
                    <SimpleEditApplication app={app} setApp={setApp} handleEditApp={handleEditApp} isDisabledButton={isDisabledButton} />
                    :
                    null
                    }
                    { app ? 
                    <SimpleEditAppDelete deleteOpen={deleteOpen} setDeleteOpen={setDeleteOpen} isDisabledButton={isDisabledButton} handleDeleteApp={handleDeleteApp}/>
                    :
                    null
                    }
                </Grid> 
                :
                null
                }
            </ScrollWrapper>
        </Box>
    </Fragment>
  )
}


export default SimpleEdit
