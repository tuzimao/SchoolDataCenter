// ** React Imports
import { useEffect, useState, Fragment } from 'react'

// ** MUI Imports
import Box from '@mui/material/Box'
import { useTheme } from '@mui/material/styles'

// ** Hooks
import { useSettings } from 'src/@core/hooks/useSettings'
import IdNotExist from 'src/pages/404IdNotExist'

// ** Chat App Components Imports
import LeftApp from 'src/views/AiFlow/edit/LeftApp'

import SimpleEdit from 'src/views/AiFlow/edit/SimpleEdit'
import PublishApp from 'src/views/AiFlow/edit/PublishApp'
import ChatlogApp from 'src/views/AiFlow/edit/ChatlogApp'
import ChatIndex from 'src/views/AiFlow/chat/ChatIndex'

// ** Axios Imports
import toast from 'react-hot-toast'
import axios from 'axios'
import { authConfig } from 'src/configs/auth'
import { useRouter } from 'next/router'
import { useAuth } from 'src/hooks/useAuth'
import { CheckPermission } from 'src/functions/ChatBook'
import { useTranslation } from 'react-i18next'
import { defaultConfig } from 'src/configs/auth'

const EditApp = (props: any) => {
  // ** States
  const [app, setApp] = useState<any>(null)
  const [isDisabledButton, setIsDisabledButton] = useState<boolean>(false)
  const [deleteOpen, setDeleteOpen] = useState<boolean>(false) 
  const [avatarFiles, setAvatarFiles] = useState<File[]>([])

  const { menuid } = props

  // ** Hooks
  const { t } = useTranslation()
  const theme = useTheme()
  const { settings } = useSettings()
  const auth = useAuth()
  const router = useRouter()
  const { id } = router.query
  useEffect(() => {
    CheckPermission(auth, router, false)
  }, [])

  const getMyApp = async function (id: string) {
    if (auth && auth.user && id && window) {
      const authorization = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
      const RS = await axios.post(authConfig.backEndApiHost + '/aiagent/workflow.php?action=getmyapp', {appId: id}, { headers: { Authorization: authorization, 'Content-Type': 'application/json'} }).then(res=>res.data)
      setApp(RS)
    }
  }

  const handleEditApp = async () => {
    console.log("handleEditApp app", app)
    setIsDisabledButton(true)
    if (auth && auth.user && window) {
      const appNew = {
        ...app,
        mode: 'simple'
      };
      
      const payload = {
        appId: String(id),
        name: appNew.name,
        _id: appNew._id,
        intro: appNew.intro,
        avatar: appNew.avatar, // Assuming appNew.avatar is a URL or base64 string
        type: appNew.type,
        permission: appNew.permission,
        data: appNew // Send the entire appNew object directly
      };
      
      const authorization = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!;
      
      const FormSubmit: any = await axios.post(
        authConfig.backEndApiHost + '/aiagent/workflow.php?action=editapp',
        payload, // Send the payload directly as JSON
        {
          headers: {
            Authorization: authorization,
            'Content-Type': 'application/json', // Set Content-Type to application/json
          },
        }
      ).then(res => res.data);

      console.log("FormSubmit", FormSubmit)
      setIsDisabledButton(false)
      if(FormSubmit?.status == "ok") {
          toast.success(t(FormSubmit.msg) as string, { duration: 4000, position: 'top-center' })
      }
      else {
          toast.error(t(FormSubmit.msg) as string, { duration: 4000, position: 'top-center' })
          if(FormSubmit && FormSubmit.msg=='Token is invalid') {
            CheckPermission(auth, router, true)
          }
      }
    }
  }

  const handleDeleteApp = async () => {
    console.log("handleEditApp app", app)
    setIsDisabledButton(true)
    if (auth && auth.user && window) {
      const authorization = window.localStorage.getItem(defaultConfig.storageTokenKeyName)!
      const PostParams = {appId: app._id}
      const FormSubmit: any = await axios.post(authConfig.backEndApiAiBaseUrl + '/api/deleteapp', PostParams, { headers: { Authorization: authorization, 'Content-Type': 'application/json'} }).then(res => res.data)
      console.log("FormSubmit", FormSubmit)
      setIsDisabledButton(false)
      if(FormSubmit?.status == "ok") {
          toast.success(t(FormSubmit.msg) as string, { duration: 4000, position: 'top-center' })
          router.push('/flow/my')
      }
      else {
          toast.error(t(FormSubmit.msg) as string, { duration: 4000, position: 'top-center' })
          if(FormSubmit && FormSubmit.msg=='Token is invalid') {
            CheckPermission(auth, router, true)
          }
      }
    }
  }

  useEffect(() => {
    if(id) {
      getMyApp(String(id))  
    }
  }, [id])

  // ** Vars
  const { skin } = settings

  return (
    <Fragment>
      {auth.user && auth.user.email && app && app._id ?
      <Box
        className='app-chat'
        sx={{
          width: '100%',
          height: '100%',
          display: 'flex',
          borderRadius: 1,
          overflow: 'hidden',
          position: 'relative',
          backgroundColor: 'background.paper',
          boxShadow: skin === 'bordered' ? 0 : 6,
          ...(skin === 'bordered' && { border: `1px solid ${theme.palette.divider}` })
        }}
      >
        {app.id ?
        <LeftApp app={app} hidden={false} menuid={menuid}/>
        :
        null
        }
        
        {menuid == 'edit' && app.id ?
        <Fragment>
          <SimpleEdit app={app} setApp={setApp} handleEditApp={handleEditApp} handleDeleteApp={handleDeleteApp} isDisabledButton={isDisabledButton} deleteOpen={deleteOpen} setDeleteOpen={setDeleteOpen} avatarFiles={avatarFiles} setAvatarFiles={setAvatarFiles}/>
          <ChatIndex app={app} userType={'User'}/>
        </Fragment>
        :
        null
        }

        {menuid == 'publish' && app.id ?
        <Fragment>
          <PublishApp appId={app.id} />
        </Fragment>
        :
        null
        }

        {menuid == 'chatlog' && app.id ?
        <Fragment>
          <ChatlogApp appId={app.id} />
        </Fragment>
        :
        null
        }

        {menuid == 'chat' && app.id ?
        <Fragment>
          <ChatIndex app={app} userType={'User'}/>
        </Fragment>
        :
        null
        }

      </Box>
      :
      null
      }
      {auth.user && auth.user.email && app && !app._id ?
      <IdNotExist id={id} module={'Ai Chat Module For App && User'}/>
      :
      null
      }
    </Fragment>
  )
}

export default EditApp

