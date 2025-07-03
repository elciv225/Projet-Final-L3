<link rel="stylesheet" href="/assets/css/commission.css">
<main class="main-content">
    <div class="discussion-header">
        <h2>Discussion: <?= htmlspecialchars($rapport->getTitre()) ?></h2>
        <div class="report-meta">
            <span>Soumis par: <?= htmlspecialchars($info_depot['nom'] . ' ' . $info_depot['prenoms']) ?></span>
            <span>Date de soumission: <?= htmlspecialchars($info_depot['date_depot']) ?></span>
        </div>
    </div>

    <div class="discussion-container">
        <div class="report-details">
            <h3>Détails du rapport</h3>
            <div class="report-info">
                <p><strong>Titre:</strong> <?= htmlspecialchars($rapport->getTitre()) ?></p>
                <p><strong>Date:</strong> <?= htmlspecialchars($rapport->getDateRapport()) ?></p>
                <p><strong>Lien:</strong> <a href="/etats/<?= htmlspecialchars($rapport->getLienRapport()) ?>" target="_blank">Voir le rapport</a></p>
            </div>
            
            <div class="voting-area">
                <h3>Vote sur la validation</h3>
                <form action="/commission/voter" method="post" id="vote-form">
                    <input type="hidden" name="rapport_id" value="<?= htmlspecialchars($rapport->getId()) ?>">
                    
                    <div class="voting-buttons">
                        <button type="button" class="vote-btn agree-btn" data-vote="approuve">Approuver</button>
                        <button type="button" class="vote-btn disagree-btn" data-vote="desapprouve">Désapprouver</button>
                    </div>
                    
                    <div class="comment-box">
                        <textarea name="commentaire" placeholder="Commentaire de vote (obligatoire pour désapprouver)..." rows="3"></textarea>
                    </div>
                    
                    <input type="hidden" name="vote" id="vote-value">
                    <button type="submit" id="submit-vote" class="submit-vote-btn" disabled>Soumettre le vote</button>
                </form>
            </div>
        </div>
        
        <div class="chat-container">
            <div class="discussion-area" id="discussionArea">
                <?php if (empty($messages)): ?>
                    <div class="no-messages">
                        <p>Aucun message dans cette discussion. Soyez le premier à commenter!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="message <?= $message['membre_commission_id'] === $utilisateur['id'] ? 'sent' : 'received' ?>">
                            <div class="message-sender"><?= htmlspecialchars($message['nom_membre'] . ' ' . $message['prenoms_membre']) ?></div>
                            <div class="message-content"><?= htmlspecialchars($message['message']) ?></div>
                            <div class="message-time"><?= htmlspecialchars(date('d/m/Y H:i', strtotime($message['date_message']))) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <form action="/commission/ajouter-message" method="post" class="message-form">
                <input type="hidden" name="discussion_id" value="<?= htmlspecialchars($discussion_id) ?>">
                <input type="hidden" name="etudiant_id" value="<?= htmlspecialchars($etudiant_id) ?>">
                <div class="message-input-area">
                    <textarea name="message" id="messageInput" placeholder="Écrire un message..." rows="2" required></textarea>
                    <button type="submit" id="sendMessageBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Scroll to bottom of discussion
        const discussionArea = document.getElementById('discussionArea');
        discussionArea.scrollTop = discussionArea.scrollHeight;
        
        // Handle voting
        const voteButtons = document.querySelectorAll('.vote-btn');
        const voteValue = document.getElementById('vote-value');
        const submitVoteBtn = document.getElementById('submit-vote');
        const commentBox = document.querySelector('textarea[name="commentaire"]');
        
        voteButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                voteButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Set vote value
                voteValue.value = this.dataset.vote;
                
                // Enable submit button
                submitVoteBtn.disabled = false;
                
                // If vote is disapprove, require comment
                if (this.dataset.vote === 'desapprouve') {
                    commentBox.setAttribute('required', 'required');
                    commentBox.focus();
                } else {
                    commentBox.removeAttribute('required');
                }
            });
        });
        
        // Form validation
        document.getElementById('vote-form').addEventListener('submit', function(e) {
            if (voteValue.value === 'desapprouve' && commentBox.value.trim() === '') {
                e.preventDefault();
                alert('Veuillez fournir un commentaire pour expliquer votre désapprobation.');
                commentBox.focus();
            }
        });
    });
</script>