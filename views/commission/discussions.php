<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet"/>
    <link href="https://use.typekit.net/gys0gor.css" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style>
        :root {
            --primary-color: #1A5E63;
            --secondary-color: #FFC857;
            --background-primary: #F9FAFA;
            --background-secondary: #F7F9FA;
            --background-input: #ECF0F1;
            --text-primary: #050E10;
            --text-secondary: #0A1B20;
            --text-disabled: #BDC3C7;
            --button-primary: #1A5E63;
            --button-primary-hover: #15484B;
            --button-secondary: #FFC857;
            --button-secondary-hover: #FCCF6C;
            --button-disabled: #E0E6E8;
            --success: rgb(102 187 106);
            --warning: rgb(255 193 7);
            --error: rgb(239 83 80);
            --info: rgb(100 181 246);
            --border-light: #DEE2E6;
            --border-medium: #CED4DA;
            --border-dark: #495057;
            --gradient-hover: linear-gradient(to bottom, rgb(240 240 240 / 80%), rgb(220 220 220 / 90%));
            --overlay: rgb(44 62 80 / 10%);
            --shadow: rgb(0 0 0 / 5%) 0px 1px 2px 0px;
            --shadow-sm: 0 2px 4px rgb(0 0 0 / 5%);
            --shadow-md: 0 6px 10px rgb(0 0 0 / 7%);
            --shadow-lg: 0 12px 20px rgb(0 0 0 / 10%);
            --input-border: #1A5E63;
            --input-focus: rgb(26 94 99 / 20%);
            --link-color: #2A8F96;
            --link-hover: #1A5E63;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --primary-color: #207D83;
                --secondary-color: #FFCA28;
                --background-primary: #1E1E1E;
                --background-secondary: #121212;
                --background-input: #2C2C2C;
                --text-primary: #F5F5F5;
                --text-secondary: #B0B0B0;
                --text-disabled: #6C6C6C;
                --button-primary: #207D83;
                --button-primary-hover: #1A666B;
                --button-secondary: #FFCA28;
                --button-secondary-hover: #FDD835;
                --button-disabled: #3A3A3A;
                --success: rgb(76 175 80);
                --warning: rgb(251 192 45);
                --error: rgb(229 57 53);
                --info: rgb(66 165 245);
                --border-light: #333333;
                --border-medium: #4F4F4F;
                --border-dark: #1A252F;
                --gradient-hover: linear-gradient(to bottom, rgb(30 30 30 / 80%), rgb(15 15 15 / 90%));
                --overlay: rgb(0 0 0 / 50%);
                --shadow: rgb(0 0 0 / 10%) 0px 1px 2px 0px;
                --shadow-sm: 0 2px 4px rgb(0 0 0 / 20%);
                --shadow-md: 0 6px 10px rgb(0 0 0 / 25%);
                --shadow-lg: 0 12px 20px rgb(0 0 0 / 30%);
                --input-border: #207D83;
                --input-focus: rgb(32 125 131 / 20%);
                --link-color: #207D83;
                --link-hover: #1A666B;
            }
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: mulish-variable, sans-serif;
            font-variation-settings: "wght" 400;
        }

        body {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
            max-width: 100vw;
            background: var(--background-secondary);
            color: var(--text-primary);
            transition: background-color 0.5s ease, color 0.5s ease;
        }

        .main-container {
            display: flex;
            width: 100%;
            height: 100vh;
            flex-direction: column;
        }

        @media (min-width: 1024px) {
            .main-container {
                flex-direction: row;
            }
        }

        .left-section {
            width: 100%;
            background-color: var(--background-primary);
            padding: 16px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
            gap: 16px;
            box-shadow: var(--shadow-md);
        }

        @media (min-width: 1024px) {
            .left-section {
                width: 280px;
                border-right: 1px solid var(--border-light);
                border-bottom: none;
                padding: 24px;
                gap: 24px;
            }
        }

        .left-section h2 {
            font-size: 1.1rem;
            font-variation-settings: "wght" 700;
            color: var(--text-primary);
        }

        @media (min-width: 1024px) {
            .left-section h2 {
                font-size: 1.25rem;
            }
        }

        .search-bar {
            display: flex;
            align-items: center;
            background-color: var(--background-input);
            border-radius: 12px;
            padding: 8px 12px;
        }

        @media (min-width: 1024px) {
            .search-bar {
                padding: 10px 14px;
            }
        }

        .search-bar input {
            flex-grow: 1;
            border: none;
            background: transparent;
            outline: none;
            color: var(--text-primary);
            font-size: 0.85rem;
        }

        @media (min-width: 1024px) {
            .search-bar input {
                font-size: 0.9rem;
            }
        }

        .search-bar input::placeholder {
            color: var(--text-disabled);
        }

        .search-bar .material-icons-outlined {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-right: 8px;
        }

        @media (min-width: 1024px) {
            .search-bar .material-icons-outlined {
                font-size: 1.25rem;
            }
        }

        .student-list {
            list-style: none;
            max-height: 150px;
            overflow-y: auto;
        }

        @media (min-width: 1024px) {
            .student-list {
                max-height: calc(100vh - 220px);
            }
        }

        .student-list::-webkit-scrollbar {
            width: 6px;
        }

        @media (min-width: 1024px) {
            .student-list::-webkit-scrollbar {
                width: 8px;
            }
        }

        .student-list::-webkit-scrollbar-thumb {
            background-color: var(--border-medium);
            border-radius: 4px;
        }

        .student-list::-webkit-scrollbar-track {
            background-color: var(--background-input);
            border-radius: 4px;
        }

        .student-item {
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 8px;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        @media (min-width: 1024px) {
            .student-item {
                padding: 14px 16px;
                border-radius: 12px;
                margin-bottom: 10px;
            }
        }

        .student-item:hover {
            background-color: var(--background-input);
            transform: translateY(-1px);
        }

        .student-item.active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .student-item.active .status-badge {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .student-name {
            font-variation-settings: "wght" 600;
            font-size: 0.9rem;
        }

        @media (min-width: 1024px) {
            .student-name {
                font-size: 1rem;
            }
        }

        .status-badge {
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 14px;
            font-variation-settings: "wght" 500;
        }

        @media (min-width: 1024px) {
            .status-badge {
                font-size: 0.75rem;
                padding: 5px 10px;
                border-radius: 16px;
            }
        }

        .status-upcoming {
            background-color: var(--info);
            color: var(--text-primary);
        }

        .status-in-progress {
            background-color: var(--warning);
            color: var(--text-primary);
        }

        .status-validated {
            background-color: var(--success);
            color: var(--text-primary);
        }

        .status-rejected {
            background-color: var(--error);
            color: var(--text-primary);
        }

        .history-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 10px;
            cursor: pointer;
            color: var(--link-color);
            transition: background-color 0.2s ease;
        }

        @media (min-width: 1024px) {
            .history-toggle {
                gap: 10px;
                padding: 12px 14px;
                border-radius: 12px;
            }
        }

        .history-toggle:hover {
            background-color: var(--background-input);
            color: var(--link-hover);
        }

        .history-toggle .material-icons-outlined {
            font-size: 1.2rem;
        }

        @media (min-width: 1024px) {
            .history-toggle .material-icons-outlined {
                font-size: 1.35rem;
            }
        }

        .central-section {
            flex-grow: 1;
            padding: 16px;
            display: flex;
            flex-direction: column;
            background-color: var(--background-secondary);
            height: calc(100vh - 220px);
        }

        @media (min-width: 1024px) {
            .central-section {
                padding: 24px 32px;
                height: 100vh;
            }
        }

        .discussion-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        @media (min-width: 1024px) {
            .discussion-header {
                margin-bottom: 20px;
            }
        }

        .discussion-header h2 {
            font-size: 1.2rem;
            font-variation-settings: "wght" 700;
        }

        @media (min-width: 1024px) {
            .discussion-header h2 {
                font-size: 1.6rem;
            }
        }

        .close-session-btn {
            background-color: var(--button-primary);
            color: white;
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-variation-settings: "wght" 600;
            cursor: pointer;
            transition: background-color 0.2s ease, box-shadow 0.2s ease;
            box-shadow: var(--shadow-sm);
        }

        @media (min-width: 1024px) {
            .close-session-btn {
                padding: 12px 20px;
                border-radius: 10px;
                font-size: 0.9rem;
            }
        }

        .close-session-btn:hover {
            background-color: var(--button-primary-hover);
            box-shadow: var(--shadow-md);
        }

        .close-session-btn:disabled {
            background-color: var(--button-disabled);
            color: var(--text-disabled);
            cursor: not-allowed;
            box-shadow: none;
        }

        .chat-container {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            overflow: hidden;
            background-color: var(--background-primary);
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            margin-bottom: 12px;
        }

        @media (min-width: 1024px) {
            .chat-container {
                margin-bottom: 20px;
            }
        }

        .discussion-area {
            flex-grow: 1;
            padding: 16px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        @media (min-width: 1024px) {
            .discussion-area {
                padding: 20px;
                gap: 16px;
            }
        }

        .message {
            padding: 10px 14px;
            border-radius: 18px;
            max-width: 80%;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        @media (min-width: 1024px) {
            .message {
                padding: 12px 18px;
                border-radius: 20px;
                max-width: 75%;
                font-size: 0.9rem;
                line-height: 1.5;
            }
        }

        .message.sent {
            background-color: var(--primary-color);
            color: white;
            align-self: flex-end;
            border-bottom-right-radius: 6px;
        }

        .message.received {
            background-color: var(--background-input);
            color: var(--text-primary);
            align-self: flex-start;
            border-bottom-left-radius: 6px;
        }

        .message-sender {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-bottom: 4px;
            font-variation-settings: "wght" 500;
        }

        @media (min-width: 1024px) {
            .message-sender {
                font-size: 0.8rem;
                margin-bottom: 6px;
            }
        }

        .message.received .message-sender {
            color: var(--text-disabled);
        }

        .message.sent .message-sender {
            color: rgba(255, 255, 255, 0.7);
        }

        .message-input-area {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            border-top: 1px solid var(--border-light);
        }

        @media (min-width: 1024px) {
            .message-input-area {
                gap: 16px;
                padding: 12px;
            }
        }

        .message-input-area textarea {
            flex-grow: 1;
            border: 1px solid var(--border-light);
            border-radius: 10px;
            padding: 10px;
            resize: none;
            font-size: 0.85rem;
            background-color: var(--background-input);
            color: var(--text-primary);
            outline: none;
            min-height: 44px;
        }

        @media (min-width: 1024px) {
            .message-input-area textarea {
                border-radius: 12px;
                padding: 12px;
                font-size: 0.9rem;
                min-height: 48px;
            }
        }

        .message-input-area textarea:focus {
            border-color: var(--input-border);
            box-shadow: 0 0 0 3px var(--input-focus);
        }

        .message-input-area button {
            background-color: var(--button-primary);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s ease;
        }

        @media (min-width: 1024px) {
            .message-input-area button {
                border-radius: 12px;
                padding: 12px;
            }
        }

        .message-input-area button:hover {
            background-color: var(--button-primary-hover);
        }

        .message-input-area button .material-icons-outlined {
            font-size: 1.4rem;
        }

        @media (min-width: 1024px) {
            .message-input-area button .material-icons-outlined {
                font-size: 1.6rem;
            }
        }

        .voting-area {
            padding: 12px;
            background-color: var(--background-primary);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            font-size: 0.85rem;
        }

        @media (min-width: 1024px) {
            .voting-area {
                padding: 16px;
                border-radius: 16px;
                box-shadow: var(--shadow-md);
                font-size: 0.9rem;
            }
        }

        .voting-area h3 {
            font-size: 0.9rem;
            font-variation-settings: "wght" 600;
            margin-bottom: 8px;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
        }

        @media (min-width: 1024px) {
            .voting-area h3 {
                font-size: 1rem;
                margin-bottom: 12px;
            }
        }

        .voting-area h3 .material-icons-outlined {
            font-size: 1.2rem;
            transition: transform 0.2s ease;
        }

        .voting-area.collapsed h3 .material-icons-outlined {
            transform: rotate(-90deg);
        }

        .voting-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        @media (min-width: 1024px) {
            .voting-content {
                gap: 12px;
            }
        }

        .voting-area.collapsed .voting-content {
            display: none;
        }

        .voting-buttons {
            display: flex;
            gap: 8px;
        }

        @media (min-width: 1024px) {
            .voting-buttons {
                gap: 10px;
            }
        }

        .vote-btn {
            flex-grow: 1;
            padding: 8px 12px;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-variation-settings: "wght" 600;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease, box-shadow 0.2s ease;
            box-shadow: var(--shadow-sm);
        }

        @media (min-width: 1024px) {
            .vote-btn {
                padding: 10px 14px;
                border-radius: 10px;
                font-size: 0.9rem;
            }
        }

        .vote-btn:active {
            transform: translateY(1px);
            box-shadow: none;
        }

        .vote-btn:hover {
            box-shadow: var(--shadow-md);
        }

        .agree-btn {
            background-color: var(--success);
            color: white;
        }

        .agree-btn:hover {
            background-color: rgb(60 155 65);
        }

        .disagree-btn {
            background-color: var(--error);
            color: white;
        }

        .disagree-btn:hover {
            background-color: rgb(210 65 60);
        }

        .voting-status {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 0.8rem;
        }

        @media (min-width: 1024px) {
            .voting-status {
                gap: 6px;
                font-size: 0.85rem;
            }
        }

        .member-vote {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            border-bottom: 1px solid var(--border-light);
        }

        @media (min-width: 1024px) {
            .member-vote {
                padding: 6px 0;
            }
        }

        .member-vote:last-child {
            border-bottom: none;
        }

        .member-name {
            color: var(--text-secondary);
        }

        .vote-indicator {
            font-size: 0.7rem;
            padding: 3px 7px;
            border-radius: 14px;
            font-variation-settings: "wght" 500;
        }

        @media (min-width: 1024px) {
            .vote-indicator {
                font-size: 0.75rem;
                padding: 4px 8px;
                border-radius: 16px;
            }
        }

        .vote-agree {
            background-color: var(--success);
            color: var(--text-primary);
        }

        .vote-disagree {
            background-color: var(--error);
            color: var(--text-primary);
        }

        .vote-pending {
            background-color: var(--background-input);
            color: var(--text-disabled);
        }

        .vote-results-summary {
            padding: 8px;
            background-color: var(--background-input);
            border-radius: 8px;
            text-align: center;
            font-variation-settings: "wght" 600;
            font-size: 0.8rem;
        }

        @media (min-width: 1024px) {
            .vote-results-summary {
                padding: 10px;
                border-radius: 10px;
                font-size: 0.85rem;
            }
        }

        .alert-disagreement {
            padding: 8px;
            background-color: var(--warning);
            color: var(--text-primary);
            border-radius: 8px;
            text-align: center;
            margin-top: 4px;
            font-variation-settings: "wght" 500;
            display: none;
            font-size: 0.8rem;
        }

        @media (min-width: 1024px) {
            .alert-disagreement {
                padding: 10px;
                border-radius: 10px;
                margin-top: 6px;
                font-size: 0.85rem;
            }
        }

        .alert-disagreement.active {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        @media (min-width: 1024px) {
            .alert-disagreement.active {
                gap: 8px;
            }
        }

        .comment-box {
            margin-top: 4px;
        }

        @media (min-width: 1024px) {
            .comment-box {
                margin-top: 6px;
            }
        }

        .comment-box textarea {
            width: 100%;
            min-height: 40px;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            padding: 6px 8px;
            resize: vertical;
            font-size: 0.8rem;
            background-color: var(--background-input);
            color: var(--text-primary);
            outline: none;
        }

        @media (min-width: 1024px) {
            .comment-box textarea {
                min-height: 50px;
                border-radius: 10px;
                padding: 8px 10px;
                font-size: 0.85rem;
            }
        }

        .comment-box textarea:focus {
            border-color: var(--input-border);
            box-shadow: 0 0 0 3px var(--input-focus);
        }

        .right-section {
            width: 100%;
            background-color: var(--background-primary);
            padding: 16px;
            border-top: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
            gap: 16px;
            box-shadow: var(--shadow-md);
        }

        @media (min-width: 768px) and (max-width: 1023px) {
            .right-section {
                width: 320px;
                border-left: 1px solid var(--border-light);
                border-top: none;
            }

            .main-container {
                flex-direction: row;
            }

            .central-section {
                height: 100vh;
            }
        }

        @media (min-width: 1024px) {
            .right-section {
                width: 320px;
                border-left: 1px solid var(--border-light);
                border-top: none;
                padding: 24px;
                gap: 24px;
            }
        }

        .right-section h3 {
            font-size: 1rem;
            font-variation-settings: "wght" 700;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-medium);
            padding-bottom: 8px;
            margin-bottom: 12px;
        }

        @media (min-width: 1024px) {
            .right-section h3 {
                font-size: 1.1rem;
                padding-bottom: 10px;
                margin-bottom: 16px;
            }
        }

        .student-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 8px;
        }

        @media (min-width: 1024px) {
            .student-profile {
                gap: 10px;
            }
        }

        .student-profile img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
            margin-bottom: 8px;
            box-shadow: var(--shadow-md);
        }

        @media (min-width: 1024px) {
            .student-profile img {
                width: 110px;
                height: 110px;
                border: 4px solid var(--primary-color);
                margin-bottom: 10px;
            }
        }

        .student-profile .name {
            font-size: 1.1rem;
            font-variation-settings: "wght" 600;
        }

        @media (min-width: 1024px) {
            .student-profile .name {
                font-size: 1.25rem;
            }
        }

        .student-profile .id {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        @media (min-width: 1024px) {
            .student-profile .id {
                font-size: 0.9rem;
            }
        }

        .report-info .info-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            padding: 6px 0;
            border-bottom: 1px dashed var(--border-light);
        }

        @media (min-width: 1024px) {
            .report-info .info-item {
                font-size: 0.9rem;
                padding: 8px 0;
            }
        }

        .report-info .info-item:last-child {
            border-bottom: none;
        }

        .report-info .info-label {
            color: var(--text-secondary);
            font-variation-settings: "wght" 500;
        }

        .report-info .info-value {
            color: var(--text-primary);
            font-variation-settings: "wght" 600;
            text-align: right;
        }

        .report-info .info-value.status-validated {
            color: var(--success);
        }

        .report-info .info-value.status-pending {
            color: var(--warning);
        }

        .report-info .info-value.status-rejected {
            color: var(--error);
        }

        .report-info .info-value.status-upcoming {
            color: var(--info);
        }

        .report-actions {
            display: flex;
            gap: 12px;
            margin-top: 6px;
        }

        @media (min-width: 1024px) {
            .report-actions {
                gap: 16px;
                margin-top: 8px;
            }
        }

        .report-action-btn {
            flex-grow: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid var(--button-primary);
            color: var(--button-primary);
            background-color: transparent;
            font-size: 0.85rem;
            font-variation-settings: "wght" 600;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
        }

        @media (min-width: 1024px) {
            .report-action-btn {
                gap: 8px;
                padding: 12px;
                border-radius: 10px;
                font-size: 0.9rem;
            }
        }

        .report-action-btn:hover {
            background-color: var(--button-primary);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .report-action-btn .material-icons-outlined {
            font-size: 1.2rem;
        }

        @media (min-width: 1024px) {
            .report-action-btn .material-icons-outlined {
                font-size: 1.35rem;
            }
        }

        .hidden {
            display: none;
        }

        .mobile-nav-toggle {
            display: block;
            padding: 8px;
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            cursor: pointer;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        @media (min-width: 1024px) {
            .mobile-nav-toggle {
                display: none;
            }

            .left-section, .right-section {
                display: flex !important;
            }
        }

        @media (max-width: 1023px) {
            .left-section:not(.mobile-visible), .right-section:not(.mobile-visible) {
                display: none;
            }

            .left-section.mobile-visible, .right-section.mobile-visible {
                display: flex;
                width: 100%;
                height: auto;
                max-height: 50vh;
                overflow-y: auto;
            }

            .main-container > *:not(.mobile-nav-toggle) {
                height: auto;
            }

            .central-section {
                min-height: 300px;
            }
        }
    </style>
</head>
<main class="main-content">
    <button class="mobile-nav-toggle" id="toggleLeftNav">Afficher Étudiants</button>
    <aside class="left-section">
        <h2>File d'attente</h2>
        <div class="search-bar">
            <span class="material-icons-outlined">search</span>
            <input placeholder="Rechercher un étudiant..." type="text"/>
        </div>
        <ul class="student-list">
            <li class="student-item active" data-student-id="1">
                <div>
                    <div class="student-name">Dupont, Jean</div>
                    <div style="font-size: 0.8em; color: var(--text-secondary);">ID: E12345</div>
                </div>
                <span class="status-badge status-in-progress">En cours</span>
            </li>
            <li class="student-item" data-student-id="2">
                <div>
                    <div class="student-name">Martin, Alice</div>
                    <div style="font-size: 0.8em; color: var(--text-secondary);">ID: E67890</div>
                </div>
                <span class="status-badge status-upcoming">À venir</span>
            </li>
            <li class="student-item" data-student-id="3">
                <div>
                    <div class="student-name">Bernard, Lucas</div>
                    <div style="font-size: 0.8em; color: var(--text-secondary);">ID: E24680</div>
                </div>
                <span class="status-badge status-upcoming">À venir</span>
            </li>
        </ul>
        <div class="history-toggle" id="toggleHistory">
            <span class="material-icons-outlined">history</span>
            <span>Historique des rapports</span>
        </div>
        <ul class="student-list hidden" id="historyList">
            <li class="student-item" data-student-id="4">
                <div>
                    <div class="student-name">Petit, Chloé</div>
                    <div style="font-size: 0.8em; color: var(--text-secondary);">ID: E13579</div>
                </div>
                <span class="status-badge status-validated">Validé</span>
            </li>
            <li class="student-item" data-student-id="5">
                <div>
                    <div class="student-name">Leroy, Tom</div>
                    <div style="font-size: 0.8em; color: var(--text-secondary);">ID: E97531</div>
                </div>
                <span class="status-badge status-rejected">Rejeté</span>
            </li>
        </ul>
    </aside>
    <main class="central-section">
        <div class="discussion-header">
            <h2>Discussion: Rapport de Jean Dupont</h2>
            <button class="close-session-btn" disabled="" id="closeSessionBtn">Clôturer la session</button>
        </div>
        <div class="chat-container">
            <div class="discussion-area" id="discussionArea">
                <div class="message received">
                    <div class="message-sender">Prof. Lemoine</div>
                    Le plan du rapport est bien structuré.
                </div>
                <div class="message sent">
                    <div class="message-sender">Vous (Prof. Durand)</div>
                    Je suis d'accord, la problématique est claire.
                </div>
                <div class="message received">
                    <div class="message-sender">Dr. Rossi</div>
                    Quelques coquilles à corriger dans la bibliographie cependant.
                </div>
            </div>
            <div class="message-input-area">
                <textarea id="messageInput" placeholder="Écrire un message..." rows="1"></textarea>
                <button id="sendMessageBtn"><span class="material-icons-outlined">send</span></button>
            </div>
        </div>
        <div class="voting-area" id="votingAreaContainer">
            <h3 id="toggleVotingArea">Vote sur la validation <span class="material-icons-outlined">expand_more</span>
            </h3>
            <div class="voting-content">
                <div class="voting-buttons">
                    <button class="vote-btn agree-btn" id="agreeBtn">D'accord</button>
                    <button class="vote-btn disagree-btn" id="disagreeBtn">Pas d'accord</button>
                </div>
                <div class="voting-status" id="votingStatus">
                    <div class="member-vote">
                        <span class="member-name">Prof. Lemoine:</span>
                        <span class="vote-indicator vote-agree" id="voteLemoine">D'accord</span>
                    </div>
                    <div class="member-vote">
                        <span class="member-name">Vous (Prof. Durand):</span>
                        <span class="vote-indicator vote-pending" id="voteDurand">En attente</span>
                    </div>
                    <div class="member-vote">
                        <span class="member-name">Dr. Rossi:</span>
                        <span class="vote-indicator vote-pending" id="voteRossi">En attente</span>
                    </div>
                </div>
                <div class="alert-disagreement" id="disagreementAlert">
                    <span class="material-icons-outlined">warning</span>
                    Désaccord. Veuillez justifier.
                </div>
                <div class="vote-results-summary hidden" id="voteResultsSummary">
                    Résultat du vote: En attente.
                </div>
                <div class="comment-box">
                    <textarea id="voteComment" placeholder="Commentaire de vote (optionnel)..." rows="2"></textarea>
                </div>
            </div>
        </div>
    </main>
    <button class="mobile-nav-toggle" id="toggleRightNav">Afficher Détails</button>
    <aside class="right-section">
        <div class="student-profile">
            <img alt="Photo de l'étudiant Jean Dupont" id="studentPhoto"
                 src="https://lh3.googleusercontent.com/aida-public/AB6AXuDwEYaUWVGkduwoKOio8dhr6iBQcfXCLmyIzcjrk7lp267K7dbsCtG6gIrRodaaNc8uuFs0MtxhPuuEFb3KTfzAOhmllCG0Yh8-N2rAMmcTsTU5OpqYOgCu0AB9TzHut3BVv9FYxrsJzZxToZ4avJ3tMIoizEIbg-To3IP1M4vsiJmahu05Ci4whJhJph7zkOWU_A-q8lxkH10306NtvHYY-2vcpDWoK8WJma-pn7q8ZFSeII4ZRhphL8nQQf5AjUSdlJ15BFtYccQ"/>
            <h3 class="name" id="studentName">Dupont, Jean</h3>
            <p class="id" id="studentId">ID: E12345</p>
        </div>
        <div class="report-info">
            <h3>Informations du rapport</h3>
            <div class="info-item">
                <span class="info-label">Titre:</span>
                <span class="info-value" id="reportTitle">Analyse de systèmes distribués</span>
            </div>
            <div class="info-item">
                <span class="info-label">Superviseur:</span>
                <span class="info-value" id="reportSupervisor">Prof. K. Anya</span>
            </div>
            <div class="info-item">
                <span class="info-label">Soumission:</span>
                <span class="info-value" id="reportDate">15/07/2024</span>
            </div>
            <div class="info-item">
                <span class="info-label">Statut:</span>
                <span class="info-value status-pending" id="reportStatus">En cours</span>
            </div>
        </div>
        <div class="report-actions">
            <button class="report-action-btn" id="downloadReportBtn">
                <span class="material-icons-outlined">download</span>
                Télécharger
            </button>
            <button class="report-action-btn" id="viewReportBtn">
                <span class="material-icons-outlined">visibility</span>
                Visualiser
            </button>
        </div>
    </aside>
</main>
<script>
    // Mock data - replace with actual data fetching
    const studentsData = {
        "1": {
            name: "Dupont, Jean",
            id: "E12345",
            photo: "image-placeholder-profile.png",
            report: {
                title: "Analyse de systèmes distribués",
                supervisor: "Prof. K. Anya",
                submissionDate: "15/07/2024",
                status: "En cours de validation",
                statusClass: "status-pending"
            }
        },
        "2": {
            name: "Martin, Alice",
            id: "E67890",
            photo: "image-placeholder-profile2.png",
            report: {
                title: "IA pour la reconnaissance d'images",
                supervisor: "Dr. B. Charles",
                submissionDate: "18/07/2024",
                status: "À venir",
                statusClass: "status-upcoming"
            }
        },
        "3": {
            name: "Bernard, Lucas",
            id: "E24680",
            photo: "image-placeholder-profile3.png",
            report: {
                title: "Sécurité des réseaux IoT",
                supervisor: "Prof. D. Elara",
                submissionDate: "20/07/2024",
                status: "À venir",
                statusClass: "status-upcoming"
            }
        },
        "4": {
            name: "Petit, Chloé",
            id: "E13579",
            photo: "image-placeholder-profile4.png",
            report: {
                title: "Optimisation d'algorithmes génétiques",
                supervisor: "Prof. F. Gael",
                submissionDate: "10/06/2024",
                status: "Validé",
                statusClass: "status-validated"
            }
        },
        "5": {
            name: "Leroy, Tom",
            id: "E97531",
            photo: "image-placeholder-profile5.png",
            report: {
                title: "Développement d'une application mobile",
                supervisor: "Dr. H. Ines",
                submissionDate: "05/05/2024",
                status: "Rejeté",
                statusClass: "status-rejected"
            }
        }
    };
    const studentItems = document.querySelectorAll('.student-item');
    const studentNameDisplay = document.getElementById('studentName');
    const studentIdDisplay = document.getElementById('studentId');
    const studentPhotoDisplay = document.getElementById('studentPhoto');
    const reportTitleDisplay = document.getElementById('reportTitle');
    const reportSupervisorDisplay = document.getElementById('reportSupervisor');
    const reportDateDisplay = document.getElementById('reportDate');
    const reportStatusDisplay = document.getElementById('reportStatus');
    const discussionHeaderTitle = document.querySelector('.central-section .discussion-header h2');
    studentItems.forEach(item => {
        item.addEventListener('click', () => {
            studentItems.forEach(i => i.classList.remove('active'));
            item.classList.add('active');
            const studentId = item.dataset.studentId;
            const student = studentsData[studentId];
            if (student) {
                studentNameDisplay.textContent = student.name;
                studentIdDisplay.textContent = `ID: ${student.id}`;
                studentPhotoDisplay.src = student.photo;
                studentPhotoDisplay.alt = `Photo de ${student.name}`;
                reportTitleDisplay.textContent = student.report.title;
                reportSupervisorDisplay.textContent = student.report.supervisor;
                reportDateDisplay.textContent = student.report.submissionDate;
                reportStatusDisplay.textContent = student.report.status;
                reportStatusDisplay.className = `info-value ${student.report.statusClass}`;
                discussionHeaderTitle.textContent = `Discussion: Rapport de ${student.name.split(', ')[1]} ${student.name.split(', ')[0]}`;
                document.getElementById('discussionArea').innerHTML = `<div class="message received"><div class="message-sender">Système</div>Bienvenue dans la discussion pour ${student.name}.</div>`;
                resetVotes();
                if (window.innerWidth < 1024) { // Auto-hide left panel on mobile after selection
                    document.querySelector('.left-section').classList.remove('mobile-visible');
                    document.getElementById('toggleLeftNav').textContent = "Afficher Étudiants";
                }
            }
        });
    });
    const toggleHistoryBtn = document.getElementById('toggleHistory');
    const historyList = document.getElementById('historyList');
    toggleHistoryBtn.addEventListener('click', () => {
        historyList.classList.toggle('hidden');
        const icon = toggleHistoryBtn.querySelector('.material-icons-outlined');
        icon.textContent = historyList.classList.contains('hidden') ? 'history' : 'expand_less';
    });
    const messageInput = document.getElementById('messageInput');
    const sendMessageBtn = document.getElementById('sendMessageBtn');
    const discussionArea = document.getElementById('discussionArea');
    sendMessageBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function sendMessage() {
        const messageText = messageInput.value.trim();
        if (messageText) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', 'sent');
            messageDiv.innerHTML = `<div class="message-sender">Vous (Prof. Durand)</div>${messageText.replace(/\n/g, '<br>')}`;
            discussionArea.appendChild(messageDiv);
            messageInput.value = '';
            discussionArea.scrollTop = discussionArea.scrollHeight;
            // Auto-resize textarea
            messageInput.style.height = 'auto';
            messageInput.style.height = messageInput.scrollHeight + 'px';
        }
    }

    messageInput.addEventListener('input', () => {
        messageInput.style.height = 'auto';
        messageInput.style.height = messageInput.scrollHeight + 'px';
    });
    const agreeBtn = document.getElementById('agreeBtn');
    const disagreeBtn = document.getElementById('disagreeBtn');
    const voteDurandDisplay = document.getElementById('voteDurand');
    const voteLemoineDisplay = document.getElementById('voteLemoine');
    const voteRossiDisplay = document.getElementById('voteRossi');
    const closeSessionBtn = document.getElementById('closeSessionBtn');
    const voteResultsSummary = document.getElementById('voteResultsSummary');
    const disagreementAlert = document.getElementById('disagreementAlert');
    let votes = {
        lemoine: 'agree',
        durand: null,
        rossi: null
    };

    function updateVoteDisplay(member, vote) {
        const displayElement = document.getElementById(`vote${member.charAt(0).toUpperCase() + member.slice(1)}`);
        if (vote === 'agree') {
            displayElement.textContent = "D'accord";
            displayElement.className = 'vote-indicator vote-agree';
        } else if (vote === 'disagree') {
            displayElement.textContent = "Pas d'accord";
            displayElement.className = 'vote-indicator vote-disagree';
        } else {
            displayElement.textContent = "En attente";
            displayElement.className = 'vote-indicator vote-pending';
        }
    }

    updateVoteDisplay('lemoine', votes.lemoine);
    agreeBtn.addEventListener('click', () => castVote('durand', 'agree'));
    disagreeBtn.addEventListener('click', () => castVote('durand', 'disagree'));

    function castVote(member, vote) {
        votes[member] = vote;
        updateVoteDisplay(member, vote);
        checkVotingStatus();
        if (member === 'durand' && !votes.rossi) {
            setTimeout(() => {
                const randomVote = Math.random() < 0.7 ? 'agree' : 'disagree';
                votes.rossi = randomVote;
                updateVoteDisplay('rossi', votes.rossi);
                checkVotingStatus();
            }, 1500);
        }
    }

    function resetVotes() {
        votes = {
            lemoine: Math.random() < 0.8 ? 'agree' : 'disagree',
            durand: null,
            rossi: null
        };
        updateVoteDisplay('lemoine', votes.lemoine);
        updateVoteDisplay('durand', votes.durand);
        updateVoteDisplay('rossi', votes.rossi);
        closeSessionBtn.disabled = true;
        voteResultsSummary.classList.add('hidden');
        voteResultsSummary.textContent = "Résultat du vote: En attente.";
        disagreementAlert.classList.remove('active');
        agreeBtn.disabled = false;
        disagreeBtn.disabled = false;
        document.getElementById('voteComment').value = '';
        checkVotingStatus();
    }

    function checkVotingStatus() {
        const allVoted = Object.values(votes).every(v => v !== null);
        if (allVoted) {
            agreeBtn.disabled = true;
            disagreeBtn.disabled = true;
            const uniqueVotes = new Set(Object.values(votes));
            if (uniqueVotes.size === 1) {
                closeSessionBtn.disabled = false;
                voteResultsSummary.textContent = `Validation ${votes.durand === 'agree' ? 'ACCEPTÉE' : 'REJETÉE'} (Unanime)`;
                voteResultsSummary.classList.remove('hidden');
                disagreementAlert.classList.remove('active');
                voteResultsSummary.style.backgroundColor = votes.durand === 'agree' ? 'var(--success)' : 'var(--error)';
                voteResultsSummary.style.color = 'white';
            } else {
                closeSessionBtn.disabled = true;
                voteResultsSummary.textContent = "Désaccord. Session non finalisable.";
                voteResultsSummary.style.backgroundColor = 'var(--warning)';
                voteResultsSummary.style.color = 'var(--text-primary)';
                voteResultsSummary.classList.remove('hidden');
                disagreementAlert.classList.add('active');
            }
        } else {
            closeSessionBtn.disabled = true;
            voteResultsSummary.classList.add('hidden');
            disagreementAlert.classList.remove('active');
            if (!votes.durand) {
                agreeBtn.disabled = false;
                disagreeBtn.disabled = false;
            }
        }
    }

    checkVotingStatus();
    closeSessionBtn.addEventListener('click', () => {
        if (!closeSessionBtn.disabled) {
            alert("Session clôturée. Le statut du rapport est mis à jour.");
            const activeStudentItem = document.querySelector('.student-item.active');
            if (activeStudentItem) {
                const statusBadge = activeStudentItem.querySelector('.status-badge');
                const allAgreed = Object.values(votes).every(v => v === 'agree');
                if (allAgreed) {
                    statusBadge.textContent = 'Validé';
                    statusBadge.className = 'status-badge status-validated';
                    reportStatusDisplay.textContent = 'Validé';
                    reportStatusDisplay.className = 'info-value status-validated';
                } else {
                    statusBadge.textContent = 'Rejeté';
                    statusBadge.className = 'status-badge status-rejected';
                    reportStatusDisplay.textContent = 'Rejeté';
                    reportStatusDisplay.className = 'info-value status-rejected';
                }
            }
            const nextStudent = activeStudentItem ? activeStudentItem.nextElementSibling : null;
            if (nextStudent && nextStudent.classList.contains('student-item')) {
                nextStudent.click();
            } else {
                discussionHeaderTitle.textContent = "File d'attente terminée";
                if (activeStudentItem) activeStudentItem.classList.remove('active');
                studentNameDisplay.textContent = "-";
                studentIdDisplay.textContent = "ID: -";
                studentPhotoDisplay.src = "";
                studentPhotoDisplay.alt = "Aucun étudiant sélectionné";
                reportTitleDisplay.textContent = "-";
                reportSupervisorDisplay.textContent = "-";
                reportDateDisplay.textContent = "-";
                reportStatusDisplay.textContent = "-";
                reportStatusDisplay.className = "info-value";
                document.getElementById('discussionArea').innerHTML = `<div class="message received"><div class="message-sender">Système</div>Aucun étudiant en cours.</div>`;
                resetVotes();
                closeSessionBtn.disabled = true;
            }
        }
    });
    const votingAreaContainer = document.getElementById('votingAreaContainer');
    const toggleVotingAreaBtn = document.getElementById('toggleVotingArea');
    toggleVotingAreaBtn.addEventListener('click', () => {
        votingAreaContainer.classList.toggle('collapsed');
    });
    // Make voting area collapsed by default
    votingAreaContainer.classList.add('collapsed');
    // Responsive navigation toggles
    const toggleLeftNavBtn = document.getElementById('toggleLeftNav');
    const toggleRightNavBtn = document.getElementById('toggleRightNav');
    const leftSection = document.querySelector('.left-section');
    const rightSection = document.querySelector('.right-section');
    toggleLeftNavBtn.addEventListener('click', () => {
        leftSection.classList.toggle('mobile-visible');
        if (leftSection.classList.contains('mobile-visible')) {
            toggleLeftNavBtn.textContent = "Cacher Étudiants";
            rightSection.classList.remove('mobile-visible'); // Hide right if left is shown
            toggleRightNavBtn.textContent = "Afficher Détails";
        } else {
            toggleLeftNavBtn.textContent = "Afficher Étudiants";
        }
    });
    toggleRightNavBtn.addEventListener('click', () => {
        rightSection.classList.toggle('mobile-visible');
        if (rightSection.classList.contains('mobile-visible')) {
            toggleRightNavBtn.textContent = "Cacher Détails";
            leftSection.classList.remove('mobile-visible'); // Hide left if right is shown
            toggleLeftNavBtn.textContent = "Afficher Étudiants";
        } else {
            toggleRightNavBtn.textContent = "Afficher Détails";
        }
    });
    // Ensure correct display on resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            leftSection.classList.remove('mobile-visible');
            rightSection.classList.remove('mobile-visible');
            toggleLeftNavBtn.textContent = "Afficher Étudiants";
            toggleRightNavBtn.textContent = "Afficher Détails";
        }
    });
</script>