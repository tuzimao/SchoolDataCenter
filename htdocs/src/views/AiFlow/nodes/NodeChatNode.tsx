// ** React Imports
import React, { Fragment, useState, useEffect, useContext } from 'react'

// ** MUI Imports
import Box from '@mui/material/Box'
import Grid from '@mui/material/Grid'
import Card from '@mui/material/Card'
import Avatar from '@mui/material/Avatar'
import Tooltip from '@mui/material/Tooltip'
import CardHeader from '@mui/material/CardHeader'
import Typography from '@mui/material/Typography'
import TextField from '@mui/material/TextField'
import Switch from '@mui/material/Switch'

import Dialog from '@mui/material/Dialog'
import DialogTitle from '@mui/material/DialogTitle'
import DialogContent from '@mui/material/DialogContent'
import DialogActions from '@mui/material/DialogActions'

import { useTranslation } from 'react-i18next'
import Divider from '@mui/material/Divider'
import HelpOutlineIcon from '@mui/icons-material/HelpOutline'

import { NodeProps, Handle, Position } from 'reactflow'
import { FlowModuleItemType } from 'src/functions/app/type'

import Button from '@mui/material/Button'
import Icon from 'src/@core/components/icon'
import IconButton from '@mui/material/IconButton'
import CloseIcon from '@mui/icons-material/Close'

import { FlowContext } from '../advanced/FlowContext'
import { getNanoid } from 'src/functions/app/string.tools'
import MyDatasetModel from 'src/views/AiFlow/components/MyDataset'
import DatasetPromptModel from 'src/views/AiFlow/components/DatasetPrompt'
import TextField2 from 'src/views/Enginee/components/TextField2'

import LLMModelModel from 'src/views/AiFlow/components/LLMModel'


const NodeChatNode = ({ data, selected }: NodeProps<FlowModuleItemType>) => {
  const { moduleId, outputs, inputs, name, id } = data;
  const { t } = useTranslation()
  
  const { setNodes, nodes, setEdges, edges } = useContext(FlowContext);

  console.log("NodeChatNode moduleId", selected, id, name, moduleId, inputs, outputs)
  console.log("NodeChatNode data", data)


  const [RenameOpen, setRenameOpen] = useState<boolean>(false)
  const [NodeTitle, setNodeTitle] = useState<string>("")

  const [MyDataset, setMyDataset] = useState<any>({MyDatasetOpen: false, MyDatasetList:[]})
  const [DatasetPrompt, setDatasetPrompt] = useState<any>({DatasetPromptOpen: false, REPHRASE_TEMPLATE: '', QA_TEMPLATE: ''})
  
  //const [TTSModel,setTTSModel] = useState<any>({TTSOpen: false, TTSValue: 'Disabled', TTSSpeed: 1})
  const [LLMModel,setLLMModel] = useState<any>({LLMModelOpen: false, 
                                                model: 'gpt-3.5-turbo', 
                                                quoteMaxToken: 2, 
                                                maxContext: 16000,
                                                functionCall: true,
                                                temperature: 0,
                                                maxResponse: 4000,
                                                maxChatHistories: 6,
                                                charsPointsPrice: 2
                                              })


  const [isOpen, setIsOpen] = useState<boolean>(false);

  const handlePopoverOpen = () => {
    setIsOpen(true);
  };

  const handlePopoverClose = () => {
    setIsOpen(false);
  };

  const handleAiModelChange = (index: number, LLMModel: any) => {
    setNodes((prevState: any)=>{
      const nodesNew = prevState.map((itemNode: any)=>{
        if(itemNode.data.id == data.id) {
          const targetNode = { ...itemNode }
          const ItemData = targetNode.data.inputs[index]
          targetNode.data.inputs[index] = { ...ItemData, value: LLMModel.model, LLMModel }
          console.log("targetNode", targetNode)

          return targetNode
        }
        else {

          return itemNode
        }
      })

      return nodesNew;
    })
  }

  const handleMyDatasetChange = (index: number, MyDataSet: any) => {
    setNodes((prevState: any)=>{
      const nodesNew = prevState.map((itemNode: any)=>{
        if(itemNode.data.id == data.id) {
          const targetNode = { ...itemNode }
          const ItemData = targetNode.data.inputs[index]
          targetNode.data.inputs[index] = { ...ItemData, MyDataSet }
          console.log("targetNode", targetNode)

          return targetNode
        }
        else {

          return itemNode
        }
      })

      return nodesNew;
    })
  }

  const handleDatasetPromptChange = (index: number, DatasetPrompt: any) => {
    setNodes((prevState: any)=>{
      const nodesNew = prevState.map((itemNode: any)=>{
        if(itemNode.data.id == data.id) {
          const targetNode = { ...itemNode }
          const ItemData = targetNode.data.inputs[index]
          targetNode.data.inputs[index] = { ...ItemData, DatasetPrompt }
          console.log("targetNode", targetNode)

          return targetNode
        }
        else {

          return itemNode
        }
      })

      return nodesNew;
    })
  }

  useEffect(() => {
    const ChatNodeInitial: any = data.inputs
    if(ChatNodeInitial && t!=null) {
      ChatNodeInitial.map((itemNode: any)=>{
        if(itemNode.type == 'AiModel') {
          console.log("setLLMModel Default", itemNode)
          setLLMModel( () => ({ ...itemNode.LLMModel, LLMModelOpen: false }) );
        }
        if(itemNode.type == 'Dataset' && itemNode.MyDataSet) {
          console.log("setMyDataset Default", itemNode)
          setMyDataset( () => ({ ...itemNode.MyDataSet, MyDatasetOpen: false }) );
        }
        if(itemNode.type == 'Dataset') {
          console.log("setDatasetPrompt Default", itemNode)
          const DatasetTemp = {...itemNode.DatasetPrompt}
          if(!DatasetTemp.REPHRASE_TEMPLATE) {
            DatasetTemp.REPHRASE_TEMPLATE = t('REPHRASE_TEMPLATE_CONTENT')
          }
          if(!DatasetTemp.QA_TEMPLATE) {
            DatasetTemp.QA_TEMPLATE = t('QA_TEMPLATE_CONTENT')
          }
          console.log("setDatasetPrompt Default", DatasetTemp)
          setDatasetPrompt( () => ({ ...DatasetTemp, DatasetPromptOpen: false }) );
        }
      })
    }
  }, [t])

  const handleRenameNode = (nodeId: string) => {
    const updatedNodes = nodes.map((node: any) => {
      if (node.id == nodeId) {

        return {
          ...node,
          data: {
            ...node.data,
            name: NodeTitle
          }
        };
      }

      return node;
    });
    setNodes(updatedNodes);
    setRenameOpen(false);
  };

  const handleCopyNode = (nodeId: string) => {
    const getNanoidValue = getNanoid(6);
    const copyNodes = nodes.map((node: any) => {
      if (node.id == nodeId) {

        return {
          ...node,
          selected: false
        };
      }

      return node;
    });
    const currentNode1 = copyNodes.filter((node: any) => {

      return node.id == nodeId
    });
    const currentNode2 = currentNode1.map((node: any) => {
      if (node.id == nodeId) {

        return {
          ...node,
          data: {
            ...node.data,
            id: getNanoidValue
          },
          position: {
            x: node.position.x + 200,
            y: node.position.y + 80,
          },
          positionAbsolute: {
            x: node.position.x + 200,
            y: node.position.y + 80,
          },
          id: getNanoidValue,
          selected: true
        };
      }
      else {

        return node;
      }
    });
    const updatedNodes = copyNodes.concat(currentNode2);
    setNodes(updatedNodes);

    //console.log("handleCopyNode", nodeId, copyNodes, currentNode1)
    //console.log("handleCopyNode updatedNodes", updatedNodes)
  };

  const handleDeleteNode = (nodeId: string) => {
    const DeletedNodes = nodes.filter((node: any) => {

      return node.id != nodeId;
    });
    setNodes(DeletedNodes);
  };

  useEffect(()=>{
    setNodeTitle(t(name) as string)
  }, [t, name])

  useEffect(()=>{
    if(selected) {
      edges.map((item: any)=>{
        if(item.target == id) {

          return {
            ...item,
            style: {
              stroke: '#00BFFF',
              strokeWidth: 4
            }
          };
        }
        else {

          return {
            ...item,
            style: {
              stroke: '#808080',
              strokeWidth: 2
            }
          };
        }
      })
    }
  }, [selected, setEdges, edges, id])
  
  return (
        <Grid container spacing={2} onMouseEnter={handlePopoverOpen} onMouseLeave={handlePopoverClose}>
          <Card sx={{ border: theme => `1px solid ${theme.palette.divider}`, width: '500px' }}>
            <CardHeader
              title={
                  <Box sx={{ display: 'flex', alignItems: 'center' }}>
                    <Avatar src={data.avatar} sx={{ mr: 2.5, width: 36, height: 36 }} />
                    <Typography sx={{ fontWeight: 600, fontSize: '1.25rem' }}>{t(name) as string}</Typography>
                  </Box>
                }
              subheader={
                <Typography variant='subtitle1'>
                  {t(data.intro) as string}
                </Typography>
              }
              titleTypographyProps={{
                sx: {
                  mb: 2.5,
                  lineHeight: '2rem !important',
                  letterSpacing: '0.15px !important'
                }
              }}
            />
            <Fragment>
              <Grid container spacing={[5, 0]}>
                <Box display="flex" mb={1} alignItems="center" justifyContent="space-between">
                  <Box position={'absolute'} left={'-2px'}>
                    <Handle
                      style={{
                        width: '14px',
                        height: '14px',
                        borderWidth: '3.5px',
                        backgroundColor: 'white',
                        top: '-3px',
                        left: '-13px',
                        borderColor: '#36ADEF'
                      }}
                      type="target"
                      id={`Triger_Left`}
                      position={Position.Left}
                    />
                  </Box>
                  <Box display="flex" alignItems="center">
                    <Typography sx={{ pl: 3, pb: 2, pr: 2 }}>{t('switch')}</Typography>
                  </Box>
                  <Typography sx={{ pr: 3, pb: 2 }}>{t('running done')}</Typography>
                  <Box position={'absolute'} right={'-2px'}>
                  <Handle
                    style={{
                      width: '14px',
                      height: '14px',
                      borderWidth: '3.5px',
                      backgroundColor: 'white',
                      top: '-3px',
                      right: '87px',
                      borderColor: '#36ADEF'
                    }}
                    type="source"
                    id={`Triger_Right`}
                    position={Position.Right}
                  />
                </Box>
                </Box>
              </Grid>
            </Fragment>

            <Divider sx={{ bgcolor: 'rgba(0, 0, 0, 0.12)' }} />
            <Grid item xs={12} sx={{ py: 2, display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
                <Typography variant="body1" sx={{ fontWeight: 'bold', textAlign: 'center' }}>
                    {t("Inputs")}
                </Typography>
            </Grid>
            <Divider sx={{ bgcolor: 'rgba(0, 0, 0, 0.12)' }} />
            
            <Grid container spacing={2} pb={5}>
              {data && data.inputs && data.inputs.length>0 && data.inputs.map((item: any, index: number) => {

                  return (<Fragment key={`inputs_${index}`}>
                          {item.type == 'AiModel' ?
                          <Fragment>
                            <Grid item sx={{pt:4}} xs={12}>
                              <Box display="flex" mb={1} pt={2} alignItems="center" justifyContent="space-between">
                                <Box display="flex" alignItems="center">
                                <Typography sx={{ pl: 2, py: 2 }}>{t(item.label)}</Typography>
                                {item && item.required && <span style={{ paddingTop: '9px', color: 'red', marginLeft: '3px' }}>*</span>}
                                </Box>
                                <Button size="small" onClick={
                                      () => { setLLMModel( (prevState: any) => ({ ...prevState, LLMModelOpen: true }) ) }
                                    }>
                                      {LLMModel.model}
                                </Button>
                              </Box>
                              <LLMModelModel LLMModel={LLMModel} setLLMModel={setLLMModel} ModelData={item} handleAiModelChange={handleAiModelChange} index={index}/>
                            </Grid>
                          </Fragment>
                          :
                          null}

                          {item.type == 'Dataset' ?
                          <Fragment>
                            <Grid item sx={{pt:4}} xs={12}>
                              <Box display="flex" pt={2} alignItems="center" justifyContent="space-between">
                                <Box display="flex" alignItems="center">
                                  <Typography sx={{ pl: 2, py: 2 }}>{t(item.label || item.key)}</Typography>
                                  {item && item.required && <span style={{ paddingTop: '9px', color: 'red', marginLeft: '3px' }}>*</span>}
                                  <Tooltip title={t(item.description)}>
                                    <HelpOutlineIcon sx={{ display: ['none', 'inline'], ml: 1 }} />
                                  </Tooltip>
                                </Box>
                                <Box display="flex" alignItems="center">
                                  <Button size="small" startIcon={<Icon icon='mdi:plus'/>} onClick={
                                        () => { 
                                          setMyDataset( (prevState: any) => ({ ...prevState, MyDatasetOpen: true }) ) 
                                        }
                                      }>
                                        {t('Select dataset')}
                                  </Button>
                                  <Button size="small" startIcon={<Icon icon='mdi:plus'/>} onClick={
                                        () => { 
                                          setDatasetPrompt( (prevState: any) => ({ ...prevState, DatasetPromptOpen: true }) ) 
                                        }
                                      }>
                                        {t('Setting quote prompt')}
                                  </Button>
                                </Box>
                              </Box>
                              <Box mb={1} pt={2}>
                                {MyDataset && MyDataset.MyDatasetList && MyDataset.MyDatasetList.map((item: any, index: number)=>{

                                  return (
                                    <Fragment key={index}>
                                      <Button sx={{mb:1, ml:1}} variant='outlined' size="small" startIcon={<Icon icon='material-symbols:database-outline' />}>
                                        {item.name}
                                      </Button>
                                    </Fragment>
                                  )
                                })}
                              </Box>
                              <MyDatasetModel MyDataset={MyDataset} setMyDataset={setMyDataset} ModelData={item} handleMyDatasetChange={handleMyDatasetChange} index={index}/>
                              <DatasetPromptModel DatasetPrompt={DatasetPrompt} setDatasetPrompt={setDatasetPrompt} ModelData={item} handleDatasetPromptChange={handleDatasetPromptChange} index={index}/>
                            </Grid>
                          </Fragment>
                          :
                          null}

                          {item.type == 'Textarea' ?
                          <Fragment>
                            <Grid item sx={{pt:4}} xs={12}>
                              <Box display="flex" mb={1} alignItems="center">
                                <Box position={'absolute'} left={'-2px'}>
                                  <Handle
                                    style={{
                                      width: '14px',
                                      height: '14px',
                                      borderWidth: '3.5px',
                                      backgroundColor: 'white',
                                      top: '2px',
                                      left: '-13px',
                                      borderColor: '#36ADEF'
                                    }}
                                    type="target"
                                    id={`${item.key}_Left`}
                                    position={Position.Left}
                                  />
                                </Box>
                                <Typography sx={{pl: 3, pt: 2, pb: 1}}>{t(item.label) as string}</Typography>
                                <Tooltip title={t(item.placeholder)} >
                                  <HelpOutlineIcon sx={{ display: ['none', 'inline'], ml: 1, pt: 1.3 }} />
                                </Tooltip>
                              </Box>
                              <TextField2
                                multiline
                                rows={6}
                                value={item.value}
                                sx={{ width: '100%', resize: 'both', '& .MuiInputBase-input': { fontSize: '0.875rem' } }}
                                placeholder={t(item.placeholder) as string}
                                onChange={(e: any) => {
                                  setNodes((prevState: any)=>{
                                    const nodesNew = prevState.map((itemNode: any)=>{
                                      if(itemNode.data.id == data.id) {
                                        const targetNode = { ...itemNode }
                                        const ItemData = targetNode.data.inputs[index]
                                        targetNode.data.inputs[index] = { ...ItemData, value: e.target.value as string }
                                        console.log("targetNode", targetNode)

                                        return targetNode
                                      }
                                      else {

                                        return itemNode
                                      }
                                    })

                                    return nodesNew;
                                  })
                                }}
                              />
                            </Grid>
                          </Fragment>
                          :
                          null}

                          {item.type == 'NumberInput' ?
                          <Fragment>
                            <Grid item sx={{pt:4}} xs={12}>
                              <Box display="flex" mb={1} alignItems="center">
                                <Typography sx={{pl: 2, pt: 2, pb: 1}}>{t(item.label) as string}</Typography>
                                {item && item.required && <span style={{ paddingTop: '9px', color: 'red', marginLeft: '3px' }}>*</span>}
                                {item.placeholder ?
                                <Tooltip title={t(item.placeholder)}>
                                  <HelpOutlineIcon sx={{ display: ['none', 'inline'], ml: 1 }} />
                                </Tooltip>                              
                                :
                                null}
                              </Box>
                              <TextField
                                type='number'
                                size='small'
                                InputProps={{ inputProps: { min: 0, max: 100 } }}
                                value={item.value}
                                sx={{ width: '100%', resize: 'both', '& .MuiInputBase-input': { fontSize: '0.875rem' } }}
                                placeholder={t(item.placeholder) as string}
                                onChange={(e: any) => {
                                  setNodes((prevState: any)=>{
                                    const nodesNew = prevState.map((itemNode: any)=>{
                                      if(itemNode.data.id == data.id) {
                                        const targetNode = { ...itemNode }
                                        const ItemData = targetNode.data.inputs[index]
                                        targetNode.data.inputs[index] = { ...ItemData, value: e.target.value as string }
                                        console.log("targetNode", targetNode)

                                        return targetNode
                                      }
                                      else {

                                        return itemNode
                                      }
                                    })

                                    return nodesNew;
                                  })
                                }}
                              />
                            </Grid>
                          </Fragment>
                          :
                          null}

                          {item.type == 'SystemInput' ?
                          <Fragment>
                            <Grid item sx={{pt:4}} xs={12}>
                              <Box display="flex" mb={1} pt={2} alignItems="center" justifyContent="space-between">
                                <Box position={'absolute'} left={'-2px'}>
                                  <Handle
                                    style={{
                                      width: '14px',
                                      height: '14px',
                                      borderWidth: '3.5px',
                                      backgroundColor: 'white',
                                      left: '-13px',
                                      borderColor: '#36ADEF'
                                    }}
                                    type="target"
                                    id={`${item.key}_Left`}
                                    position={Position.Left}
                                  />
                                </Box>
                                <Box display="flex" alignItems="center">
                                  <Typography sx={{ pl: 3, py: 2 }}>{t(item.toolDescription)}</Typography>
                                  {item && item.required && <span style={{ paddingTop: '9px', color: 'red', marginLeft: '3px' }}>*</span>}
                                </Box>
                                <Typography sx={{ pr: 3, py: 2 }}>{t(item.toolDescription)}</Typography>
                                <Box position={'absolute'} right={'-2px'}>
                                <Handle
                                  style={{
                                    width: '14px',
                                    height: '14px',
                                    borderWidth: '3.5px',
                                    backgroundColor: 'white',
                                    right: '87px',
                                    borderColor: '#36ADEF'
                                  }}
                                  type="source"
                                  id={`${item.key}_Right`}
                                  position={Position.Right}
                                />
                              </Box>
                              </Box>
                            </Grid>
                          </Fragment>
                          :
                          null}

                          {item.key == 'Variables' ?
                          <Fragment>
                            <Grid item sx={{pt: 7, pb: 1}} xs={12}>
                              <Box display="flex" mb={1} alignItems="center">
                                <Avatar src={'/icons/core/app/simpleMode/variable.svg'} variant="rounded" sx={{ width: '32px', height: '32px'}} />
                                <Typography sx={{pl: 2, pt: 2, pb: 1}}>{t(item.label) as string}</Typography>
                                <Tooltip title={t('variableTip')}>
                                  <HelpOutlineIcon sx={{ display: ['none', 'inline'], ml: 1 }} />
                                </Tooltip>
                                <Box position={'absolute'} right={'10px'}>
                                  <Button variant='outlined' size="small" startIcon={<Icon icon='mdi:add' />} >
                                  {t("Add")}
                                  </Button>
                                </Box>
                              </Box>
                            </Grid>
                            <Grid item xs={12}>
                              <Divider sx={{ bgcolor: 'rgba(0, 0, 0, 0.12)' }} />
                            </Grid>
                          </Fragment>
                          :
                          null}

                          {item.key == 'QuestionGuide' ?
                          <Fragment>
                            <Grid item xs={12}>
                              <Box display="flex" mb={1} alignItems="center">
                                <Avatar src={'/icons/core/chat/QGFill.svg'} variant="rounded" sx={{ width: '28px', height: '28px'}} />
                                <Typography sx={{pl: 2, pt: 2, pb: 1}}>{t(item.label) as string}</Typography>
                                <Tooltip title={t('questionGuideTip')}>
                                  <HelpOutlineIcon sx={{ display: ['none', 'inline'], ml: 1 }} />
                                </Tooltip>
                                <Box position={'absolute'} right={'10px'}>
                                  <Switch 
                                        checked={!!item.value} 
                                        onChange={(e: any) => {
                                            setNodes((prevState: any)=>{
                                                const nodesNew = prevState.map((itemNode: any)=>{
                                                  if(itemNode.data.id == data.id) {
                                                    const targetNode = { ...itemNode }
                                                    const ItemData = targetNode.data.inputs[index]
                                                    targetNode.data.inputs[index] = { ...ItemData, value: !!e.target.checked }
                                                    console.log("targetNode", targetNode)

                                                    return targetNode
                                                  }
                                                  else {

                                                    return itemNode
                                                  }
                                                })

                                                return nodesNew;
                                            })
                                        }}
                                    />
                                </Box>
                              </Box>
                            </Grid>
                            <Grid item xs={12}>
                              <Divider sx={{ bgcolor: 'rgba(0, 0, 0, 0.12)' }} />
                            </Grid>
                          </Fragment>
                          :
                          null}

                          </Fragment>)
              })
              }
            </Grid>

            <Divider sx={{ bgcolor: 'rgba(0, 0, 0, 0.12)' }} />
            <Grid item xs={12} sx={{ py: 2, display: 'flex', justifyContent: 'center', alignItems: 'center' }}>
                <Typography variant="body1" sx={{ fontWeight: 'bold', textAlign: 'center' }}>
                    {t("Outputs")}
                </Typography>
            </Grid>
            <Divider sx={{ bgcolor: 'rgba(0, 0, 0, 0.12)' }} />

            <Grid container spacing={2}>
              {data && data.outputs && data.outputs.length>0 && data.outputs.map((item: any, index: number) => {

                  return (<Fragment key={`outputs_${index}`}>
                          {item.type == 'source' ?
                          <Fragment>
                            <Grid item xs={12}>
                              <Box display="flex" mb={1} pt={2} alignItems="center" justifyContent="flex-end">
                                <Typography sx={{ pr: 3, py: 2 }}>{t(item.label)}</Typography>
                              </Box>
                              <Box position={'absolute'} right={'-2px'}>
                                <Handle
                                  style={{
                                    width: '14px',
                                    height: '14px',
                                    borderWidth: '3.5px',
                                    backgroundColor: 'white',
                                    top: '-22px',
                                    right: '87px',
                                    borderColor: '#36ADEF'
                                  }}
                                  type="source"
                                  id={`${item.key}_Right`}
                                  position={Position.Right}
                                />
                              </Box>
                            </Grid>
                          </Fragment>
                          :
                          null}

                  </Fragment>)
              })
              }
            </Grid>

          </Card>

          <Dialog maxWidth='xs' fullWidth open={RenameOpen} onClose={() => {
                                                                    setRenameOpen(false)
                                                                  }}>
            <DialogTitle>
            <Box display="flex" alignItems="center">
              <Avatar src={'/icons/core/app/simpleMode/tts.svg'} variant="rounded" sx={{ width: '25px', height: '25px', pl: 1}} />
              <Typography sx={{pl: 2, pt: 2, pb: 1}}>{t('Custom Title') as string}</Typography>
              <Box position={'absolute'} right={'5px'} top={'1px'}>
                <IconButton size="small" edge="end" onClick={() => { setRenameOpen(false) } } aria-label="close">
                  <CloseIcon />
                </IconButton>
              </Box>
            </Box>
            </DialogTitle>
            <DialogContent sx={{  }}>
              <Grid item xs={12}>
                  <TextField
                    defaultValue={NodeTitle}
                    sx={{ width: '100%', resize: 'both', '& .MuiInputBase-input': { fontSize: '0.875rem' } }}
                    placeholder={t(NodeTitle) as string}
                    onChange={(e: any) => { setNodeTitle(e.target.value) }}
                  />
              </Grid>
            </DialogContent>
            <DialogActions>
              <Button size="small" variant='outlined' onClick={() => { setRenameOpen(false) } }>
                {t("Close")}
              </Button>
              <Button size="small" variant='outlined' onClick={()=>handleRenameNode(id)}>
                {t("Confirm")}
              </Button>
            </DialogActions>
          </Dialog>

          {isOpen ? 
            <Grid container direction="column" spacing={2} sx={{ width: '100px' }}>
              <Grid item sx={{ml: 2, mt: 0, width: '100px'}}>
                <Button size="small" variant='outlined' startIcon={<Icon icon='mdi:rename-box-outline' />} onClick={() => { setRenameOpen(true) } }>
                  {t('Rename')}
                </Button>
              </Grid>
              <Grid item sx={{ml: 2, mt: 0, width: '100px'}}>
                <Button size="small" variant='outlined' startIcon={<Icon icon='mdi:pencil-outline' />} onClick={()=>handleCopyNode(id)}>
                  {t('Copy')}
                </Button>
              </Grid>
              <Grid item sx={{ml: 2, mt: 0, width: '100px'}}>
                <Button size="small" variant='outlined' startIcon={<Icon icon='mdi:delete-outline' />} onClick={()=>handleDeleteNode(id)}>
                  {t('Delete')}
                </Button>
              </Grid>
            </Grid>
          :
          <Grid container direction="column" spacing={2} sx={{ width: '100px' }}>
            <Grid item sx={{ml: 2, mt: 0, width: '100px'}}>
            </Grid>
          </Grid>
          }
        </Grid>
  );
};


export default React.memo(NodeChatNode);
