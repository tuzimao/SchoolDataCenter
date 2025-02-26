//import { authConfig } from 'src/configs/auth'

export const simpleChat: any = {
    "_id": "660d13ad361cf48ed91321fb",
    "teamId": "65ad85ddb9c540f1cfe0f22f",
    "id": "simpleChat",
    "name": "simpleChat",
    "avatar": 'authConfig.logo',
    "intro": "一个极其简单的 AI 应用，你可以绑定知识库或工具。",
    "type": "simpleChat",
    "mode": 'simple',
    "modules": [
      {
          "id": "userGuideNodeInitial_1",
          "type": "userGuide",
          "data": {
              "id": "userGuideNodeInitial_1",
              "templateType": "userGuide",
              "flowType": "userGuide",
              "avatar": "/imgs/module/userGuide.png",
              "name": "System Setting",
              "intro": "userGuideTip",
              "inputs": [
                  {
                      "key": "WelcomeText",
                      "type": "hidden",
                      "valueType": "string",
                      "label": "WelcomeText",
                      "showTargetInApp": false,
                      "showTargetInPlugin": false,
                      "value": "welcomeTextInitial"
                  },
                  {
                      "key": "Variables",
                      "type": "hidden",
                      "valueType": "any",
                      "label": "ModuleVariable",
                      "value": [],
                      "showTargetInApp": false,
                      "showTargetInPlugin": false
                  },
                  {
                      "key": "QuestionGuide",
                      "valueType": "boolean",
                      "type": "switch",
                      "value": true,
                      "label": "QuestionGuide",
                      "showTargetInApp": false,
                      "showTargetInPlugin": false
                  },
                  {
                      "key": "TTS",
                      "type": "hidden",
                      "valueType": "any",
                      "label": "Tts",
                      "value": "Disabled",
                      "speed": 1,
                      "showTargetInApp": false,
                      "showTargetInPlugin": false,
                  }
              ],
              "outputs": [],
              "moduleId": "userGuide"
          },
          "position": {
              "x": 454.98510354678695,
              "y": 721.4016845336229
          }
      },
      {
          "id": "questionInputNodeInitial_1",
          "type": "questionInput",
          "data": {
              "id": "questionInputNodeInitial_1",
              "templateType": "SystemInput",
              "flowType": "questionInput",
              "avatar": "/imgs/module/userChatInput.svg",
              "name": "Chat entrance",
              "intro": "userChatInputIntro",
              "inputs": [
                  {
                      "key": "userChatInput",
                      "type": "SystemInput",
                      "valueType": "string",
                      "label": "user question",
                      "showTargetInApp": false,
                      "showTargetInPlugin": false
                  }
              ],
              "outputs": [
                  {
                      "key": "questionInputNodeInitial_1_output",
                      "label": "user question",
                      "type": "source",
                      "valueType": "string",
                      "targets": [
                          {
                              "moduleId": "Targets***moduleId",
                              "key": "Targets***key"
                          }
                      ]
                  }
              ],
              "moduleId": "questionInputNodeInitial_1_moduleId"
          },
          "position": {
              "x": 450,
              "y": 1350
          }
      },
      {
          "id": "chatNodeInitial_1",
          "type": "chatNode",
          "data": {
              "id": "chatNodeInitial_1",
              "templateType": "textAnswer",
              "flowType": "chatNode",
              "avatar": "/imgs/module/AI.png",
              "name": "AI Chat",
              "intro": "AI Model Chat",
              "showStatus": true,
              "isTool": true,
              "inputs": [
                  {
                      "key": "AiModel",
                      "type": "AiModel",
                      "label": "AiModel",
                      "required": true,
                      "valueType": "string",
                      "showTargetInApp": false,
                      "showTargetInPlugin": false,
                      "value": "gpt-3.5-turbo",
                      "LLMModel": {
                        LLMModelOpen: false, 
                        model: 'gpt-3.5-turbo', 
                        name: 'Chatgpt-3.5',
                        quoteMaxToken: 2, 
                        maxContext: 16000,
                        functionCall: true,
                        temperature: 0,
                        maxResponse: 4000,
                        maxChatHistories: 6,
                        charsPointsPrice: 2
                      }
                  },
                  {
                      "key": "systemPrompt",
                      "type": "Textarea",
                      "max": 3000,
                      "valueType": "string",
                      "value": "",
                      "label": "NodeChatPrompt",
                      "description": "chatNodeSystemPromptTip",
                      "placeholder": "chatNodeSystemPromptTip",
                      "showTargetInApp": true,
                      "showTargetInPlugin": true
                  },
                  {
                      "key": "history",
                      "type": "NumberInput",
                      "label": "chat history",
                      "required": true,
                      "min": 0,
                      "max": 30,
                      "valueType": "chatHistory",
                      "value": 6,
                      "showTargetInApp": true,
                      "showTargetInPlugin": true
                  },
                  {
                      "key": "userChatInput",
                      "type": "SystemInput",
                      "label": "",
                      "required": true,
                      "valueType": "string",
                      "showTargetInApp": true,
                      "showTargetInPlugin": true,
                      "toolDescription": "user question"
                  },
                  {
                      "key": "Dataset",
                      "type": "Dataset",
                      "label": "KnowledgeBaseRef",
                      "description": "Input description",
                      "valueType": "datasetQuote",
                      "showTargetInApp": true,
                      "showTargetInPlugin": true
                  },
                  {
                      "key": "AiSettings",
                      "type": "AiSettings",
                      "label": "",
                      "valueType": "any",
                      "showTargetInApp": false,
                      "showTargetInPlugin": false,
                      "connected": false
                  }
              ],
              "outputs": [
                  {
                      "key": "answerText",
                      "label": "Ai response content",
                      "description": "Ai response content",
                      "valueType": "string",
                      "type": "source",
                      "targets": []
                  },
                  {
                      "key": "finish",
                      "label": "",
                      "description": "",
                      "valueType": "boolean",
                      "type": "hidden",
                      "targets": []
                  },
                  {
                      "key": "history",
                      "label": "New context",
                      "description": "New context",
                      "valueType": "chatHistory",
                      "type": "source",
                      "targets": []
                  },
                  {
                      "key": "userChatInput",
                      "label": "user question",
                      "type": "hidden",
                      "valueType": "string",
                      "targets": []
                  }
              ],
              "moduleId": "chatModule"
          },
          "position": {
              "x": 1150.8317145593148,
              "y": 720
          }
      }
    ],
    "edges":  [
      {
        id: "bmf6r1",
        source: "questionInputNodeInitial_1",
        sourceHandle: "userChatInput",
        target: "chatNodeInitial_1",
        targetHandle: "userChatInput_Left",
        type: "buttonedge",
        animated: true,
        style: { stroke: '#00BFFF', strokeWidth: 4 }
      },
      {
        id: "bmf6r2",
        source: "questionInputNodeInitial_1",
        sourceHandle: "userChatInput",
        target: "chatNodeInitial_1",
        targetHandle: "systemPrompt_Left",
        type: "buttonedge",
        animated: true,
        style: { stroke: '#00BFFF', strokeWidth: 4 }
      }
    ],
    "permission": "private",
    "teamTags": [],
    "updateTime": "2024-04-03T08:30:37.267Z"
}
