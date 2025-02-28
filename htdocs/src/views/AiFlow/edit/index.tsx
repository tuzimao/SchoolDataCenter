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
import ChatIndex from 'src/views/AiChat/ChatIndex'

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
  const [pageModel, setPageModel] = useState<string>('Main')
  const [isDisabledButton, setIsDisabledButton] = useState<boolean>(false)
  const [deleteOpen, setDeleteOpen] = useState<boolean>(false) 
  const [avatarFiles, setAvatarFiles] = useState<File[]>([])
  const [historyCounter, setHistoryCounter] = useState<number>(0)

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
      const appData = await axios.post(authConfig.backEndApiHost + '/aiagent/workflow.php?action=getmyapp', {appId: id}, { headers: { Authorization: authorization, 'Content-Type': 'application/json'} }).then(res=>res.data)
      console.log("appData", appData)

      //重置appData数据
      const WelcomeText = GetWelcomeTextFromApp(appData)
      appData['WelcomeText'] = WelcomeText
      const SystemPrompt = GetSystemPromptFromApp(appData)
      appData['SystemPrompt'] = SystemPrompt
      const AppModel = GetModelFromApp(appData)
      appData['AppModel'] = AppModel
      appData['QuestionGuideTemplate'] = "你是一个AI智能助手，可以回答和解决我的问题。请结合前面的对话记录，以用户自己的角度，帮用户生成 3 个问题，用于引导用户来进行继续提问。要求提问的角度要站在用户的角度生成提问句子。问题的长度应小于20个字符，要求使用UTF-8编码，按 JSON 格式返回: [\"问题1\", \"问题2\", \"问题3\"]"
      appData['SimilarQuestions'] = 3

      setApp(appData)
    }
  }


  const GetSystemPromptFromApp = (app: any) => {
    const AiNode = app.modules.filter((item: any)=>item.type == 'chatNode')
    if(AiNode && AiNode[0] && AiNode[0].data && AiNode[0].data.inputs) {
      const systemPromptList = AiNode[0].data.inputs.filter((itemNode: any)=>itemNode.key == 'systemPrompt')
      if(systemPromptList && systemPromptList[0] && systemPromptList[0]['value']) {
        const systemPromptText = systemPromptList[0]['value']

        return systemPromptText
      }
    }
    
    return ''
  }

  const GetModelFromApp = (app: any) => {
    const AiNode = app.modules.filter((item: any)=>item.type == 'chatNode')
    if(AiNode && AiNode[0] && AiNode[0].data && AiNode[0].data.inputs) {
      const modelList = AiNode[0].data.inputs.filter((itemNode: any)=>itemNode.key == 'AiModel')
      if(modelList && modelList[0] && modelList[0]['value']) {

        return modelList[0]
      }
    }
    
    return ''
  }

  // const GetDatasetFromApp = (app: any) => {
  //   const AiNode = app.modules.filter((item: any)=>item.type == 'chatNode')
  //   if(AiNode && AiNode[0] && AiNode[0].data && AiNode[0].data.inputs) {
  //     const modelList = AiNode[0].data.inputs.filter((itemNode: any)=>itemNode.key == 'Dataset')
  //     if(modelList && modelList[0] && modelList[0]['MyDataSet'] && modelList[0]['MyDataSet']['MyDatasetList']) {
  //       const MyDatasetList = modelList[0]['MyDataSet']['MyDatasetList']
  //       const MyDatasetIdList = MyDatasetList.map((item: any) => item.value)
  //       console.log("GetDatasetFromApp MyDatasetIdList", MyDatasetIdList)

  //       return {MyDatasetIdList, DatasetPrompt: modelList[0]['DatasetPrompt']}
  //     }
  //   }
    
  //   return 
  // }

  const GetWelcomeTextFromApp = (app: any) => {
    const AiNode = app.modules.filter((item: any)=>item.type == 'userGuide')
    if(AiNode && AiNode[0] && AiNode[0].data && AiNode[0].data.inputs) {
      console.log("WelcomeText", AiNode)
      const systemPromptList = AiNode[0].data.inputs.filter((itemNode: any)=>itemNode.key == 'WelcomeText')
      if(systemPromptList && systemPromptList[0] && systemPromptList[0]['value']) {
        const WelcomeText = t(systemPromptList[0]['value'])

        return WelcomeText
      }
    }

    return ''
  }

  // const GetQuestionGuideFromApp = (app: any) => {
  //   const AiNode = app.modules.filter((item: any)=>item.type == 'userGuide')
  //   if(AiNode && AiNode[0] && AiNode[0].data && AiNode[0].data.inputs) {
  //     console.log("GetQuestionGuideFromApp", AiNode)
  //     const systemPromptList = AiNode[0].data.inputs.filter((itemNode: any)=>itemNode.key == 'QuestionGuide')
  //     if(systemPromptList && systemPromptList[0] && systemPromptList[0]['value']) {
  //       const QuestionGuide = systemPromptList[0]['value']

  //       return QuestionGuide
  //     }
  //   }

  //   return ''
  // }

  // const GetTTSFromApp = (app: any) => {
  //   const AiNode = app.modules.filter((item: any)=>item.type == 'userGuide')
  //   if(AiNode && AiNode[0] && AiNode[0].data && AiNode[0].data.inputs) {
  //     console.log("GetQuestionGuideFromApp", AiNode)
  //     const GetTTSFromAppList = AiNode[0].data.inputs.filter((itemNode: any)=>itemNode.key == 'tts')
  //     if(GetTTSFromAppList && GetTTSFromAppList[0] && GetTTSFromAppList[0]['value']) {
  //       const GetTTSFromAppText = GetTTSFromAppList[0]

  //       return GetTTSFromAppText
  //     }
  //   }

  //   return null
  // }

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
          <ChatIndex authConfig={authConfig} app={app} historyCounter={historyCounter} setHistoryCounter={setHistoryCounter} pageModel={pageModel} setPageModel={setPageModel} userType={'User'}/>
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
          <ChatIndex authConfig={authConfig} app={app} historyCounter={historyCounter} setHistoryCounter={setHistoryCounter} pageModel={pageModel} setPageModel={setPageModel} userType={'User'}/>
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

