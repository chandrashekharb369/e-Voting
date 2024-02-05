e_voting Systm

Electronic voting (e-voting) systems enable citizens to cast their votes electronically, often through secure online platforms or electronic voting machines. These systems aim to streamline the voting process, enhance accessibility, and reduce manual errors. Security measures, such as encryption and authentication protocols, are crucial to ensure the integrity and confidentiality of votes. E-voting can offer real-time results, but it also raises concerns about potential cybersecurity threats and the need for transparent and auditable systems to maintain public trust in the electoral process.

In the described 
voting system:
User Authentication:Users log in using their Voter ID and initially, their password is set as their date of birth.Users can change their password upon first login for added security.

User Profile:
The user profile includes information such as name, address, and constituency.Users have the option to update their profile details.

Voting Interface:
The initial page displays two buttons: one for the user's profile and another for accessing the ballot paper.The ballot paper is dynamic, showing candidates based on the user's district.

Dynamic Ballot Paper:
Candidates are dynamically displayed based on the user's district to ensure relevance.The system tracks the time a user spends on the ballot paper, and if the vote isn't submitted within 60 seconds, it's considered a NOTA vote.Security Measures:Robust authentication mechanisms to protect user accounts.Encryption for secure data transmission.User-initiated password changes for enhanced security.

Admin Actions

:Add Voters: 
Admin can add voters, specifying their details and district.Add Candidates: Admin can add candidates, associating them with specific districts in Karnataka.

Delete Voters/Candidates: Admin can delete voters and candidates. Deleted voter details are stored in a separate database with reasons and deletion dates.

Candidate Information:Age of Candidates: The system stores and displays the age of each candidate.

Live Polling Dashboard
District-wise Live Polling: Admin can view live polling data, segmented by districts in Karnataka.Graphical RepresentationThe system provides a graphical representation of total votes cast and individual candidate performance in real-time.

Security Measures:Admin Authentication: Secure login mechanisms for admin access.Audit Trail: Tracking admin actions, especially deletions, with reasons and timestamps for accountability.

Challenges:
Real-time Data Updates: Ensuring the live polling dashboard is continuously updated.Data Integrity: Safeguarding the integrity of deleted voter details and reasons.User Interface for Admin: Developing an intuitive interface for admin functionalities.